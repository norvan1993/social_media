<?php

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        //$this->call(UserSeeder::class);
        $users = factory(App\User::class, 10)
            ->create()
            ->each(function ($user) {
                factory(App\Post::class, 2)->create(['user_id' => $user->id]);
            });
    }
}
