<?php
/*
 * 	@author: $rachow
 * 	@copyright: Coinhoppa
 *
 * 	Verify admin account is not banned.
*/

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckAdminIsBanned
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
	    if (auth('admins')->check() && auth('admins')->user()->banned == '1') {
            $message = 'Your account has been suspended. Please contact administrator';

		    if (auth('admins')->user()->banned_until && now()->lessThan(auth('admins')->user()->banned_until)) {
		        $bannedInDays = now()->diffInDays(auth('admins')->user()->banned_until);
			    	if ($bannedInDays < 14) {
					    $message = 'Your account is suspended for ' . $bannedInDays . ' ' . 
						Str::plural('day', $bannedInDays) . '. Please contact administrator';
			    	}
		    }

            Auth::guard('admins')->logout();
		    return redirect()->route('login','login=failed&notice=banned')->withError($message);
	    }

	    return $next($request);
    }
}
