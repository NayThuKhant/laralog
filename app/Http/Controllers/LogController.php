<?php

namespace App\Http\Controllers;

use App\Enums\LogStatusEnum;
use App\Models\Log;
use App\Models\LogLevel;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class LogController extends Controller
{
    public function index()
    {
        $perPage = request()->get("perPage");
        $page = request()->get("page");
        $logLevelTable = (new LogLevel())->getTable();
        $logTable = (new Log())->getTable();

        $levelOptions = [];
        LogLevel::select("level")->get()->each(function (LogLevel $logLevel) use (&$levelOptions) {
            $levelOptions[$logLevel->level] = $logLevel->level;
        })->toArray();

        $statusOptions = [];
        $logStatuses = array_column(LogStatusEnum::cases(), "value");
        collect($logStatuses)->each(function ($logStatus) use (&$statusOptions) {
            $statusOptions[$logStatus] = $logStatus;
        });


        $logs = QueryBuilder::for(Log::class)
            ->join($logLevelTable, "$logTable.log_level_id", "$logLevelTable.id")
            ->allowedSorts(["level", "content", "latest_reported_at", "total_incidents"])
            ->defaultSort("-latest_reported_at")
            ->allowedFilters([
                AllowedFilter::exact(name: "level", internalName: "$logLevelTable.level"),
                AllowedFilter::exact(name: "status", internalName: "$logTable.status"),
                AllowedFilter::partial(name: "global", internalName: "$logTable.content")
            ])
            ->groupBy(["$logTable.content", "$logLevelTable.level", "$logTable.status"])
            ->select([
                "$logLevelTable.level",
                "$logTable.content",
                "$logTable.status",
                DB::raw("MAX($logTable.created_at) as latest_reported_at"),
                DB::raw("COUNT($logTable.id) as total_incidents")
            ])
            ->paginate(perPage: $perPage, page: $page)
            ->withQueryString()
            ->toArray();

        return Inertia::render("Logs/Index", [
            "logs" => $logs
        ])->table(function (InertiaTable $table) use ($logLevelTable, $logTable, $levelOptions, $statusOptions) {
            $table->withGlobalSearch()
                ->column(key: "level", label: 'Level', sortable: true)
                ->column(key: "content", label: 'Content', sortable: true)
                ->column(key: "status", label: 'Status', sortable: true)
                ->column(key: "total_incidents", label: 'Total Incidents', sortable: true)
                ->column(key: "latest_reported_at", label: 'Latest Reported At', sortable: true)
                ->selectFilter(key: "level", options: $levelOptions, label: "Level")
                ->selectFilter(key: "status", options: $statusOptions, label: "Status");
        });
    }
}
