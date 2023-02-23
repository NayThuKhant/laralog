<?php

namespace App\Http\Controllers\Jetstream;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\TeamSecretKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\RedirectsActions;

class TeamController extends Controller
{
    use RedirectsActions;

    public function show(Request $request, $teamId)
    {
        $team = Jetstream::newTeamModel()->findOrFail($teamId);

        Gate::authorize('view', $team);

        $team->load('owner', 'users', 'teamInvitations', 'secretKeys');
        return Jetstream::inertia()->render($request, 'Teams/Show', [
            'team' => $team,
            'availableRoles' => array_values(Jetstream::$roles),
            'availablePermissions' => Jetstream::$permissions,
            'defaultPermissions' => Jetstream::$defaultPermissions,
            'permissions' => [
                'canAddTeamMembers' => Gate::check('addTeamMember', $team),
                'canDeleteTeam' => Gate::check('delete', $team),
                'canRemoveTeamMembers' => Gate::check('removeTeamMember', $team),
                'canUpdateTeam' => Gate::check('update', $team),
            ],
        ]);
    }

    public function generateSecretToken($teamId)
    {
        $team = Jetstream::newTeamModel()->findOrFail($teamId);

        $team->secretKeys()->create();

        session()->flash("flash.bannerStyle", "success");
        session()->flash("flash.banner", "Secret token has been successfully generated");

        return back(303);
    }

    public function destroySecretToken(Team $team, TeamSecretKey $key)
    {
        Gate::authorize("update", $team);
        $key->delete();

        session()->flash("flash.bannerStyle", "success");
        session()->flash("flash.banner", "Secret token has been successfully deleted");

        return back(303);
    }
}
