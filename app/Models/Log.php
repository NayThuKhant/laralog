<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Log extends Model
{
    protected $fillable = ["log_level_id", "context", "message"];

    protected $casts = ["context" => "array"];

    public function getTable()
    {
        if (Auth::user() instanceof User) {
            $team = Team::find(Auth::user()->current_team_id);
            return $team->log_table;
        } else if (Auth::user() instanceof Team) {
            return Auth::user()->log_table;
        }

        return $table ?? parent::getTable();
    }

    public function logLevel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LogLevel::class);
    }

    public function identicalLogs(): Attribute
    {
        return Attribute::make(
            get: function () {
                return Log::where("log_level_id", $this->log_level_id)
                    ->where("status", $this->status)
                    ->where("message", $this->message)
                    ->select(["id", "created_at", "context"])
                    ->get()
                    ->filter(fn (Log $log) => $this->context === $log->context);
            });

    }
}
