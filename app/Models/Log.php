<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Log extends Model
{
    protected $fillable = ['log_level_id', 'context', 'message'];

    protected $casts = ['context' => 'array'];

    public function getTable()
    {
        if (Auth::user() instanceof User) {
            $team = Team::find(Auth::user()->current_team_id);

            return $team->log_table;
        } elseif (Auth::user() instanceof Team) {
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
                return Log::ofIdenticalLogs($this)
                    ->select(['id', 'created_at', 'context'])
                    ->get()
                    ->filter(fn (Log $log) => $this->context === $log->context);
            });
    }

    public function scopeOfIdenticalLogs(Builder $builder, Log $log): Builder
    {
        return $builder->where('log_level_id', $log->log_level_id)
            ->where('status', $log->status)
            ->where('message', $log->message);
    }
}
