<?php

namespace App\Console\Commands;

use Carbon\Carbon;

class GenerateApiTokenForUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:user:token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate API token with custom TTL for existing user';

    /**
     * Instance of the service used to interact with users.
     *
     * @var \App\Repositories\Contracts\UserRepository
     */
    protected $userRepository;

    /**
     * Create a new command instance.
     *
     * @param  \App\Repositories\Contracts\UserRepository $userRepository
     * @return void
     */
    public function __construct(\App\Repositories\Contracts\UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Ask for user and date
        $user = $this->askForUser();
        $ttl = $this->askForExpiryDate();

        // Ask for confirmation
        $this->comment(sprintf(
            'An API token for %s (%s, "%s" role) with expiry date %s (aprox. %s) will be generated',
            $user->getName(),
            $user->getEmail(),
            $user->getRole(),
            $ttl->toDateTimeString(),
            $ttl->diffForHumans()
        ));

        if (! $this->confirm('Do you wish to continue?'))
            return;

        // Generate token
        $token = auth('api')->setTTL($ttl->diffInMinutes())->fromUser($user);
        $this->comment('Please write down this token:');
        $this->info($token);
    }

    /**
     * Ask for user until a valid one is provided.
     *
     * @return \App\Models\User
     */
    protected function askForUser(): \App\Models\User
    {
        $user = false;

        while (! $user) {
            if ($user === null)
                $this->error('User not found. Please try again');

            $id = $this->ask('Enter user ID or e-mail address');
            $user = (str_contains($id, '@')) ? $this->userRepository->findBy('email', $id) : $this->userRepository->find($id);
        }

        return $user;
    }

    /**
     * Ask for token expiry date until a valid one is provided.
     *
     * @return \Carbon\Carbon
     */
    protected function askForExpiryDate(): Carbon
    {
        $date = false;

        while (! $date) {
            try {
                if ($date === null)
                    $this->error('Invalid date. Please try again');

                $description = $this->ask("Enter expiry date of token.\n
                You can use relative time such:
                    - tomorrow
                    - next sunday
                    - 1 week
                    - first day of next month
                    - 3 months 2 days
                    - next year\n
                or an absolute time such:
                    - first day of December 2020
                    - 2020-12-31");

                $date = Carbon::parse($description);
                if (! $date->isFuture())
                    $date = null;
            } catch (\Exception $exception) {
                $date = null;
            }
        }

        return $date;
    }
}
