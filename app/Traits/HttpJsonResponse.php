<?php
/**
 * 	@author: $rachow
 * 	@copyright: Coinhoppa
 *
 * 	Trait for sharing HTTP Json response format.
 */

namespace App\Traits;

use Illuminate\Http\Request;

trait HttpJsonResponse
{
    /*
     * wrapper for JSON response.
     *
     * @return json
     */
    protected function returnJsonResponse($data = '', $code = 200, $pretty = false)
    {
	    // clean before send.
        return response()->clean_json([
		    'data' => $data
	    ], $code, $pretty);
    }    
}
