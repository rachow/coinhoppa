<?php
/**
 *  @author: $rachow
 *  @copyright: Coinhoppa
 *
 *  To check IP address whitelist.
 */

namespace App\Http\Middleware\Api;

use Log;
use Closure;
use Illuminate\Http\Request;
use App\Traits\ApiJsonResponse;
use Symfony\Component\HttpFoundation\IpUtils;

class CheckWhitelistIpAddress
{
    use ApiJsonResponse;

    private $whitelistIps = [
        //
    ];

    private $whitelistIpRanges = [
        //
    ];

    private $isBlockedAccess = false;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure $next
     * @return mixed
    */
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->getClientIp();
        if (!$this->validIpAddress($ip) || !$this->validIpAddressRange($ip)) {
            $this->isBlockedAccess = true;
            return $this->errorResponse('You are restricted from access.', 403);
        }
        return $next($request);
    }

    /**
     * Verify IP address is valid.
     *
     * @param  $ip
     * @return boolean
     */
    private function validIpAddress($ip)
    {
        $whitelistIpAddresses = config('whitelist.ips', $this->whitelistIps);
        return (!$whitelistIpAddresses) ? true : in_array($ip, $whitelistIpAddresses);
    }

    /**
     * Verify IP address valid in range.
     *
     * @param  $ip
     * @return boolean
     */
    private function validIpAddressRange($ip)
    {
        $whitelistIpAddressRanges = config('whitelist.ranges', $this->whitelistIpRanges);
        return (!$whitelistIpAddressRanges) ? true : IpUtils::checkIp($ip, $whitelistIpAddressRanges); 
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function terminate($request, $response)
    {
        if ($this->isBlockedAccess) {
            // Log::info('Incoming request blocked.', $request);
        }
    }
}
