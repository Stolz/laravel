<?php

namespace App\Console\Commands;

use App\Models\User;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

    /**
     * Instance of the service used to interact with roles.
     *
     * @var \App\Repositories\Contracts\RoleRepository
     */
    protected $roleRepository;

    /**
     * Instance of the service used to interact with users.
     *
     * @var \App\Repositories\Contracts\UserRepository
     */
    protected $userRepository;

    /**
     * Create a new command instance.
     *
     * @param  \App\Repositories\Contracts\RoleRepository $roleRepository
     * @param  \App\Repositories\Contracts\UserRepository $userRepository
     * @return void
     */
    public function __construct(
        \App\Repositories\Contracts\RoleRepository $roleRepository,
        \App\Repositories\Contracts\UserRepository $userRepository
    ) {
        parent::__construct();
        $this->roleRepository = $roleRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Check there are roles available
        $roles = $this->roleRepository->all();
        if ($roles->isEmpty())
            return $this->error('No roles found. Is the database seeded?');

        // Ask for user details
        $user = User::make([
            'role' => $role = $this->askForRole($roles),
            'email' => $email = $this->askForEmail(),
            'name' => $name = $this->askForName($email),
            'password' => $this->askForPassword(),
        ]);

        // Prompt for confirmation
        $userInfo = compact('role', 'name', 'email');
        $this->table(array_keys($userInfo), [$userInfo]);
        if (! $this->confirm('About to create user. Do you wish to continue?', 'yes'))
            return;

        // Attempt to save user into the repository
        $created = $this->userRepository->create($user);

        // Success
        if ($created)
            return $this->info('User successfully created');

        // Something went wrong
        $this->error('Unable to save user into database');
    }

    /**
     * Ask for role until a valid one is selected.
     *
     * @param  \Illuminate\Support\Collection $roles
     * @return \App\Models\Role
     */
    protected function askForRole(\Illuminate\Support\Collection $roles): \App\Models\Role
    {
        $options = $roles->map->toArrayOnly(['id', 'name', 'description'])->sortBy('id')->all();
        $validAnswers = $roles->pluck('id')->all();
        $answer = null;

        while (! in_array($answer, $validAnswers)) {
            if ($answer !== null)
                $this->error('Invalid role ID');

            $this->table(['Id', 'Name', 'Description'], $options);
            $answer = $this->ask("Enter user role Id");
        }

        return $roles->first(function ($role) use ($answer) {
            return $role->getId() == $answer;
        });
    }

    /**
     * Ask for email address until a valid one is provided.
     *
     * @return string
     */
    protected function askForEmail(): string
    {
        $ok = null;

        while (! $ok) {
            if ($ok === false)
                $this->error('Invalid e-mail or already in use');

            $email = $this->ask("Enter user e-mail address");
            $ok = filter_var($email, FILTER_VALIDATE_EMAIL) and ! $this->userRepository->findBy('email', $email);
        }

        return $email;
    }

    /**
     * Ask for name until a valid one is provided.
     *
     * @param  string $email
     * @return string
     */
    protected function askForName(string $email): string
    {
        $suggestion = title_case(str_replace(['.', '_'], ' ', strtok($email, '@')));
        $minLenth = 2;
        $name = $ok = null;

        while (! $ok) {
            if ($name !== null)
                $this->error("Invalid name. It must be at least $minLenth characters");

            $name = trim($this->anticipate("Enter user full name", [$suggestion]));
            $ok = strlen($name) >= $minLenth;
        }

        return $name;
    }

    /**
     * Ask for password until a valid one is provided.
     *
     * @return string
     */
    protected function askForPassword(): string
    {
        $minLenth = User::MIN_PASSWORD_LENGTH;
        $password = $ok = null;

        while (! $ok) {
            if ($password !== null)
                $this->error("Invalid password. It must be at least $minLenth characters");

            $password = $this->secret("Enter user password");
            $ok = strlen($password) >= $minLenth;
        }

        return $password;
    }
}
