<?php
/**
* 	@author:  $rachow
*	@copyright: Coinhoppa
*
*	Ensuring caller accepts JSON format.
*/

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use App\Traits\ApiJsonResponse;

class JsonRequestValidate
{
    use ApiJsonResponse;

 	const QUERY_JSON_FORMAT = 'format';

	/**
     * Handle incoming request.
     *
	 * @param  \Illuminate\Http\Request
     * @param  \Closure
     * @return mixed
	*/
	public function handle(Request $request, Closure $next)
	{
        if (!$request->isJson() && !($request->query(self::QUERY_JSON_FORMAT) == 'json')) {
            return $this->errorResponse(
                'Request Invalid. "Content-Type" must be set to \'application/json\'', 400
            );
		}
		return $next($request);
	}
}
