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
     *
     * @throws \Exception
     */
    public function run(): void
    {
        $logLevels = LogLevel::select('id')->get()->pluck('id')->toArray();
        $logStatuses = array_column(LogStatusEnum::cases(), 'value');
        $messages = ['Application Exception', 'Just Logging'];

        Team::select('log_table')->get()->pluck('log_table')->each(function ($logTable) use ($messages, $logStatuses, $logLevels) {
            $logModel = (new Log())->setTable($logTable);

            \Arr::map(range(1, Arr::random([500, 1000, 2000])), function () use ($messages, $logModel, $logLevels, $logStatuses) {
                $message = $messages[array_rand($messages)];
                $logStatus = $logStatuses[array_rand($logStatuses)];
                $context = random_int(0, 1) ? ['error' => 'Beep Beep! Beep Beep! Beep Beep! Beep Beep! Beep Beep! Beep Beep! Beep Beep! Beep Beep!'.Arr::random($logLevels)] : null;

                $payload = [
                    'log_level_id' => Arr::random($logLevels),
                    'context' => $context,
                    'status' => $logStatus,
                    'message' => $message,
                ];

                $logModel->create($payload);
            });
        });
    }
}
