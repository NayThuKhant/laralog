<?php

namespace App\Http\Controllers;

use App\Models\LogLevel;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $logLevelCounts = LogLevel::withCount("logs")->get()->pluck("logs_count", "level");

        return Inertia::render("Dashboard", [
            "logLevelCounts" => $logLevelCounts
        ]);
    }
}
