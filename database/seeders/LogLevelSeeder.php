<?php

namespace Database\Seeders;

use App\Enums\LogLevelEnum;
use App\Models\LogLevel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class LogLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $logLevels = array_column(LogLevelEnum::cases(), "value");
        $logLevels = Arr::map($logLevels, function ($logLevel) {
            return ["level" => $logLevel];
        });
        LogLevel::insert($logLevels);
    }
}
