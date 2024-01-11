<?php
/**
 *  @author: $rachow
 *  @copyright: Coinhoppa
 *
 *  Traders Dashboard.
 *
*/

namespace App\Http\Controllers;

use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $agent = new Agent();
	    $browser = $agent->browser();
	    $os	 = $agent->platform();
	    $version = $agent->version($browser);
	    $locale	 = $agent->languages();
	    $device	 = $agent->device();
	    $referer = $request->headers->get('referer');
        $ip = $request->ip();

        // build json storable.
	    $event = json_encode([
	        'ip' => $request->ip(),
	        'agent' => $browser,
	        'os' => $os,
	        'version' => $version,
	        'device' => $device,
	        'referer' => $referer,
	    ]);

        // call service to send async data ?

        // render view
        return view('dashboard.dashboard');
    }
}
