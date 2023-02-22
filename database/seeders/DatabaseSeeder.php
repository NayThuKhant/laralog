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

        $this->call([
            LogLevelSeeder::class,
            LogSeeder::class
        ]);

        User::truncate();

        $userAttributes = [
            "password" => Hash::make("password"),
        ];

        $user = User::create([
            "name" => "Nay Thu Khant",
            "email" => "naythukhant@onenex.co",
            ...$userAttributes,
            "current_team_id" => 1
        ]);

        User::create([
            "name" => "Nay Thu Khant",
            "email" => "naythukhant644@gmail.com",
            ...$userAttributes,
            "current_team_id" => 0
        ]);

        Arr::map([["name" => "Yoma Living App (Development)"], ["name" => "Yoma Living App (Production)"]], function ($team) use ($user) {
            Team::create(["user_id" => $user->id, "personal_team" => false, ...$team]);
        });
    }
}
