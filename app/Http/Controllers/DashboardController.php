<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\LogLevel;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $logLevelTable = (new LogLevel())->getTable();
        $logTable = (new Log())->getTable();

        $logLevelCounts = DB::table($logLevelTable)
            ->leftJoin($logTable, "$logLevelTable.id", '=', "$logTable.log_level_id")
            ->select("$logLevelTable.level", DB::raw("count($logTable.id) as total"),
                DB::raw("sum(case when $logTable.status = 'unresolved' then 1 else 0 end) as unresolved"),
                DB::raw("sum(case when $logTable.status = 'resolved' then 1 else 0 end) as resolved"))
            ->groupBy("$logLevelTable.level")
            ->orderBy("$logLevelTable.level")
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->level => ['total' => $item->total, 'unresolved' => $item->unresolved, 'resolved' => $item->resolved]];
            })
            ->toArray();

        return Inertia::render("Dashboard", [
            "logLevelCounts" => $logLevelCounts
        ]);
    }
}
