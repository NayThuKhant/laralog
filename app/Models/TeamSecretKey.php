<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TeamSecretKey extends Model
{
    protected $fillable = ['team_id', 'encrypted_secret_key'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function (TeamSecretKey $teamSecretKey) {
            do {
                $randomString = str_shuffle(Str::random(18));
            } while (TeamSecretKey::where('encrypted_secret_key', $randomString)->first());

            $teamSecretKey->encrypted_secret_key = $randomString;
        });
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
