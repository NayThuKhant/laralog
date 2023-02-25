<?php

namespace App\Http\Controllers;

use App\Enums\LogStatusEnum;
use App\Http\Requests\StoreLogRequest;
use App\Models\Log;
use App\Models\LogLevel;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class LogController extends Controller
{
    public function index()
    {
        $perPage = request()->get('perPage');
        $page = request()->get('page');
        $logLevelTable = (new LogLevel())->getTable();
        $logTable = (new Log())->getTable();

        $levelOptions = [];
        LogLevel::select('level')->get()->each(function (LogLevel $logLevel) use (&$levelOptions) {
            $levelOptions[$logLevel->level] = $logLevel->level;
        })->toArray();

        $statusOptions = [];
        $logStatuses = array_column(LogStatusEnum::cases(), 'value');
        collect($logStatuses)->each(function ($logStatus) use (&$statusOptions) {
            $statusOptions[$logStatus] = $logStatus;
        });

        $logs = QueryBuilder::for(Log::class)
            ->join($logLevelTable, "$logTable.log_level_id", "$logLevelTable.id")
            ->allowedSorts(['level', 'message', 'latest_reported_at', 'total_incidents', 'status'])
            ->defaultSort('-latest_reported_at')
            ->allowedFilters([
                AllowedFilter::exact(name: 'level', internalName: "$logLevelTable.level"),
                AllowedFilter::exact(name: 'status', internalName: "$logTable.status"),
                AllowedFilter::partial(name: 'global', internalName: "$logTable.message"),
            ])
            ->groupBy(["$logTable.context", "$logTable.message", "$logLevelTable.level", "$logTable.status"])
            ->select([
                "$logLevelTable.level",
                //DB::raw("LEFT($logTable.context, 10) AS context"),
                "$logTable.status",
                "$logTable.message",
                DB::raw("MAX($logTable.created_at) as latest_reported_at"),
                DB::raw("MAX($logTable.id) as id"),
                DB::raw("COUNT($logTable.id) as total_incidents"),
                DB::raw("'' as actions"),
            ])
            ->paginate(perPage: $perPage, page: $page)
            ->withQueryString();

        return Inertia::render('Logs/Index', [
            'logs' => $logs,
        ])->table(function (InertiaTable $table) use ($levelOptions, $statusOptions) {
            $table->withGlobalSearch()
                ->column(key: 'actions', label: 'Actions', canBeHidden: false)
                ->column(key: 'level', label: 'Level', sortable: true)
                ->column(key: 'message', label: 'Message', sortable: true)
                ->column(key: 'status', label: 'Status', sortable: true)
                ->column(key: 'total_incidents', label: 'Total Incidents', sortable: true)
                ->column(key: 'latest_reported_at', label: 'Latest Reported At', sortable: true)
                ->selectFilter(key: 'level', options: $levelOptions, label: 'Level')
                ->selectFilter(key: 'status', options: $statusOptions, label: 'Status');
        });
    }

    public function store(StoreLogRequest $storeLogRequest)
    {
        $logLevelId = LogLevel::where('level', $storeLogRequest->get('level'))->first()->id;
        Log::create([
            'log_level_id' => $logLevelId,
            'context' => $storeLogRequest->get('context'),
            'message' => $storeLogRequest->get('message'),
        ]);

        return response()->json([
            'message' => 'Log has been reported successfully',
        ], 201);
    }

    public function show(Log $log)
    {
        $identicalLogs = $log->identical_logs;

        return Inertia::render('Logs/Show', [
            'log' => $log->load('logLevel'),
            'context' => json_encode($log->context),
            'reports' => [
                'identical_logs' => $identicalLogs,
                'identical_log_ids' => $identicalLogs->pluck('id')->toArray(),
                'total_incidents' => $identicalLogs->count(),
                'one_day_incidents' => $identicalLogs->where('created_at', '>', now()->subDay())->count(),
                'one_week_incidents' => $identicalLogs->where('created_at', '>', now()->subWeek())->count(),
                'one_month_incidents' => $identicalLogs->where('created_at', '>', now()->subMonth())->count(),
                'timestamps' => $identicalLogs->sortByDesc('created_at')->pluck('created_at')->toArray(),
            ],
        ]);
    }
}
