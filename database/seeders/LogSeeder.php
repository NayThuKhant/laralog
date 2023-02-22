<?php

namespace Database\Seeders;

use App\Enums\LogStatusEnum;
use App\Models\Log;
use App\Models\LogLevel;
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

        \Arr::map(range(1, 1000), function () use ($logLevels,  $logStatuses) {
            $level = Arr::random($logLevels);
            $content = [
                "error" => "helloworld" . Arr::random($logLevels)
            ];
            $status = $logStatuses[Arr::random([0,1])];

            Log::create(["log_level_id" =>  $level, "content" =>  $content, "status" => $status]);
        });
    }
}
