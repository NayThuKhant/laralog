<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogController;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|-------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/", function () {
    return Inertia::render("Welcome", [
        "canLogin" => Route::has("login"),
        "canRegister" => Route::has("register"),
        "laravelVersion" => Application::VERSION,
        "phpVersion" => PHP_VERSION,
        "totalUserCount" => User::count(),
        "totalTeamCount" => Team::count()
    ]);
})->name("home");

Route::middleware(["auth:sanctum", config("jetstream.auth_session"), "verified", "must.select.team"])->group(function () {
    Route::get("/dashboard", DashboardController::class)->name("dashboard");
    Route::get("/logs", [LogController::class, "index"])->name("logs.index");
    Route::get("/logs/{log}", [LogController::class, "show"])->name("logs.show");
});

require_once "jetstream.php";
