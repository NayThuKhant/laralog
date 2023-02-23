<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Artisan::call("migrate:fresh");

        User::truncate();

        $userAttributes = [
            "password" => Hash::make("password"),
        ];

        $user[] = User::create([
            "name" => "Nay Thu Khant",
            "email" => "superadmin@gmail.com",
            ...$userAttributes,
            "current_team_id" => 0
        ]);

        $user[] = User::create([
            "name" => "Nay Thu Khant",
            "email" => "naythukhant644@gmail.com",
            ...$userAttributes,
            "current_team_id" => 0
        ]);

        Arr::map([
            ["name" => "Laralog (DEV)"],
            ["name" => "Laralog (QA)"],
            ["name" => "Laralog (UAT)"],
            ["name" => "Laralog (PROD)"]
        ], function ($team) use ($user) {
            $user = Arr::random($user);
            Team::create(["user_id" => $user->id, "personal_team" => false, ...$team]);
        });

        $this->call([
            LogLevelSeeder::class,
            LogSeeder::class
        ]);
    }
}
