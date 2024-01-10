<?php
/**
 *  @author: $rachow
 *  @copyright: Coinhoppa
 *
 *  Simple authentication using HTTP Basic.
 *      to change to JWT/Token based "Bearer".
 */

namespace App\Http\Middleware\Api;

use Closure;
use Exception;
use Illuminate\Http\Request;
use App\Traits\ApiJsonResponse;

class BasicAuthenticate
{
    use ApiJsonResponse;

    /**
     * @var - all uris to exclude from basic authentication.
     */
    private $exclude_uri = [
        'api/ping', 
    ];

    /*
     * Handle incoming request.
     *
	 * @param  \Illuminate\Http\Request
	 * @param  \Closure
	 * @return  mixed
	 */
	public function handle(Request $request, Closure $next)
	{
		$username = $request->getUser();
		$password = $request->getPassword();

		if (!$username && !$password) {
            $auth_header = $request->header('Authorization');

			if ($auth_header) {
				list($username, $password) = explode(':', base64_decode(substr($auth_header, 6)));
			}
		}

        if ($this->checkByPassAuthorization($request) || 
            config('basicauth.users')->contains($username, $password)) {
            
            return $next($request);
		}

        return $this->errorResponse('Authentication failed.', 401);
	}

	/**
     * Verify whether current URL is bypass
     * 
	 * @param  Illuminate\Http\Request
	 * @return boolean
	 */
	private function checkByPassAuthorization(Request $request)
	{
		$url = $request->path();
		if (in_array($url, $this->exclude_uri)) {
			return true;
		}
		return false;
	}
}
