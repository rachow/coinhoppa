<?php
/**
 *  @author: $rachow
 *  @copyright: Coinhoppa
 *
 *  Capture request context data on requests.
 *  
 *  - To add AWS Trace Id from Gateway
 *
 */

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RequestLoggingContext
{
    /**
     * Handle incoming request.
     *
     * @param  \Illuminate\Http\Request
     * @param  \Closure
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        $requestId = Str::uuid()->toString();

        // we will trace request through all apps.
        $traceId = $request->header('X-Trace-Id') ?? 'CH-' . $requestId;

        Log::withContext([
            'Request-Id' => $requestId,
            'X-Trace-Id' => $traceId,
        ]);

        return $next($request)
            ->header('Request-Id', $requestId)
            ->header('X-Trace-Id', $traceId);
    }
}
