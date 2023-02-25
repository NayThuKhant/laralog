<?php

namespace App\Auth;

use App\Models\TeamSecretKey;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class TeamGuard implements Guard
{
    use GuardHelpers;

    protected $request;

    public function __construct($provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
    }

    public function team()
    {
        return $this->user();
    }

    public function user()
    {
        if (! is_null($this->user)) {
            return $this->user;
        }

        $errorMessage = 'Invalid team secret key';

        $teamSecretKey = $this->request->header('X-TEAM-SECRET-KEY');
        abort_if(! $teamSecretKey, 401, $errorMessage);

        $teamSecretKey = TeamSecretKey::where('encrypted_secret_key', $teamSecretKey)->first();
        $team = optional($teamSecretKey)->team;

        abort_if(! $team, 401, $errorMessage);
        $this->user = $team;

        return $this->user;
    }

    public function validate(array $credentials = [])
    {
        // Not needed for this guard
    }

    public function setUser(\Illuminate\Contracts\Auth\Authenticatable $user)
    {
        $this->user = $user;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }
}
