<?php
/**
 * 	@author: $rachow
 * 	@copyright: Coinhoppa
 *
 * 	Tracking the admins online activity.
 *
*/

namespace App\Http\Middleware;

use Log;
use Cache;
use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckLastAdminActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
	    if (auth('admins')->check()) {
			$expires_at = Carbon::now()->addMinutes(5);
			Cache::put('admin-online-' . auth()->user()->id, true, $expires_at);
			$this->storeActivityTimestamp($request);
	    }

	    return $next($request);
	}

	/**
	 * Store the last activity of admin user.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return void
	*/
	private function storeActivityTimestamp(Request $request)
	{
		$dailyActiveAdmins = [];
		$admin = $request->user();
		$lastActivity = Carbon::now();

		if (Cache::has('daily_active_admins')) {
			$dailyActiveAdmins = Cache::get('daily_active_admins');
			if (!is_array($dailyActiveAdmins)) {
				Cache::forget('daily_active_admins');
				$this->storeActivityTimestamp($request);
				return;
			}
			$dailyActiveAdmins['cdate_old'] = $dailyActiveAdmins['cdate'] ?? '--empty--'; // debug
		} else {
			$dailyActiveAdmins[$admin->id] = $lastActivity;
			$dailyActiveAdmins['cdate'] = Carbon::now()->toTimeString() . '-' . $request->url(); // debug
		}

		// push changes without ORM. by-passing any ORM events/observers
		if (!Cache::has('admin-activity-' . $admin->id)) {
			DB::table('admins')
                ->where('id', '=', $admin->id)
                ->update(['last_activity' => $lastActivity]);
            
            Cache::put('admin-activity-' . $admin->id, true, Carbon::now()->addMinutes(5));
		}
	}
}
