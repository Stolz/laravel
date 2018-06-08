<?php

use App\Models\Notification;
use App\Repositories\Contracts\UserRepository;
use App\Repositories\Contracts\NotificationRepository;
use Illuminate\Database\Seeder;

class LocalNotificationsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Get all users
        $users = app(UserRepository::class)->all();

        // Add a few notifications for each user
        $notificationRepository = app(NotificationRepository::class);

        foreach ($users as $user) {
            for ($i = 1; $i <= 5; $i++) {
                $notification = factory(Notification::class)->make(['user' => $user]);
                $notificationRepository->create($notification);
            }
        }
    }
}
