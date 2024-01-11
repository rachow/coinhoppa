<?php
/**
 *  @author: $rachow
 *	@copyright: Coinhoppa
 *
 * 	API Base Controller
 *
*/

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiJsonResponse;

class ApiController extends Controller
{
	use ApiJsonResponse;

    /**
     * Creates an instance.
     *
     * @return void
    */
	public function __construct()
	{
		//
	}
}
