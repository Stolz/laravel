<?php

use App\Models\Notification;

class LocalNotificationsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Add a few notifications for each user
        $users = $this->userRepository->all();
        foreach ($users as $user) {
            for ($i = 1; $i <= 21; $i++) {
                $notification = factory(Notification::class)->make(['user' => $user]);
                $this->notificationRepository->create($notification);
            }
        }
    }
}
