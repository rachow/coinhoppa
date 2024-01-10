<?php
/**
* 	@author: $rachow
* 	@copyright: Coinhoppa
*
*	Handle blacklisting IP/Range addresses.
*/

namespace App\Http\Middleware\Api;

use Log;
use Closure;
use Illuminate\Http\Request;
use App\Traits\ApiJsonResponse;
use Symfony\Component\HttpFoundation\IpUtils;

class CheckBlacklistIpAddress
{
    use ApiJsonResponse;

	protected $blacklistIps = [
		// '127.0.0.1'
	];

	protected $blacklistIpRange = [
	    //	
	];

    private $isBlockedAccess = false;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->validIpAddress($request->getClientIp()) || 
            $this->validIpAddressRange($request->getClientIp())) {
            
            $this->isBlockedAccess = true;
            return $this->errorResponse('You are restricted from access.', 403);
		}
        return $next($request);
    }

	/**
     * Check valid ip address against list.
     * 
	 * @param  $ip_addr
	 * @return boolean
	 */
	protected function validIpAddress($ip)
	{
		$blacklistedIpAddresses = config('blacklist.ips', $this->blacklistIps);
		return (!$blacklistedIpAddresses) ? false : in_array($ip, $blacklistedIpAddresses);
	}

	/**
     * Check valid ip address range against list.
     *
	 * @param  $ip_addr
	 * @return boolean
	 */
	protected function validIpAddressRange($ip)
	{
		$blacklistIpRangeAddresses = config('blacklist.ranges', $this->blacklistIpRange);
		return (!$blacklistIpRangeAddresses) ? false : IpUtils::checkIp($ip, $blacklistIpRangeAddresses);
	}

	/**
     * Used to perform tasks post the response sent.
     * 
	 * @param $request
	 * @param $response
	 */
	public function terminate($request, $response)
	{
		// tap into incoming request and ship to logging hub inc. X-Trace-Id ?
		// Log::info('ip blacklist checked.');
	}
}
