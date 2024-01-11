<?php
/**
 * 	@author: $rachow
 * 	@copyright: Coinhoppa
 *
 *	API Health Status Controller.
 *
*/

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;

class HealthController extends ApiController
{
    /**
     * Creates an instance and loads parent.
     *
     * @return
    */
    public function __construct()
    {
        parent::__construct();
    } 

    /**
     * Return the current status of the API.
     *
     * @param  Illuminate\Http\Request
     * @return Illuminate\Http\JsonResponse
     */
    public function getStatus(Request $request)
	{
		return $this->successResponse('OK');
	}
}
