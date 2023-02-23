<?php

namespace Database\Seeders;

use App\Enums\LogStatusEnum;
use App\Models\Log;
use App\Models\LogLevel;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class LogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $logLevels = LogLevel::select("id")->get()->pluck("id")->toArray();
        $logStatuses = array_column(LogStatusEnum::cases(), "value");

        Team::select("log_table")->get()->pluck("log_table")->each(function ($logTable) use ($logStatuses, $logLevels) {
            $logModel = (new Log())->setTable($logTable);

            \Arr::map(range(1, Arr::random([500, 1000, 2000])), function () use ($logModel, $logLevels, $logStatuses) {
                $payload = [
                    "log_level_id" => Arr::random($logLevels),
                    "content" => [
                        "error" => "Beep Beep! Beep Beep! Beep Beep! Beep Beep! Beep Beep! Beep Beep! Beep Beep! Beep Beep!" . Arr::random($logLevels)
                    ],
                    "status" => $logStatuses[Arr::random([0,1])]
                ];

                $logModel->create($payload);
            });
        });
    }
}
