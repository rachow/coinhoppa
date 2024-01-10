<?php
/**
* 	@author:  $rachow
*	@copyright: Coinhoppa
*
* 	To beautify the JSON data response
*/

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class JsonPrettyPrint
{
	const QUERY_IDENTIFIER = 'pretty';

	/**
     * Handle incoming request.
     *
	 * @param  \Illimuniate\Http\Request
     * @param  \Closure
     * @return mixed
	*/
	public function handle(Request $request, Closure $next)
	{
		$response = $next($request);
		if ($response instanceof JsonResponse) {
			if ($request->query(self::QUERY_IDENTIFIER) == 'true') {
				$response->setEncodingOptions(JSON_PRETTY_PRINT);
			}
		}
		return $response;
	}
}
