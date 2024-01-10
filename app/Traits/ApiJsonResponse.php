<?php
/**
 * 	@author: $rachow
 * 	@copyright: Coinhoppa
 *
 * 	Trait provides a cleaner way to share methods.
 *	- Add Transformers league/fractal ?
 *
*/

namespace App\Traits;

use Illuminate\Http\Request;

trait ApiJsonResponse
{
	/**
     * return success JSON wrapper.
     *
	 * @var $message - nice message
	 * @var $data	 - payload data to return
	 * @var $code	 - defaults to 200 (OK)
	*/
	protected function successResponse($message = 'OK', $data = '', $code = 200)
	{
		// verify that code is valid for http status
		if (!($code >= 200 && $code < 300)) {
			$code = 200;
		}

		// return cleaned version of data
		return response()->clean_json([
			'code' 		=> $code,
			'message'	=> $message,
			'data'		=> $data,
		], $code);
	}

	/**
	 * return failed JSON wrapper.
	 *
	 * @var $message - nice message
	 * @var $hint	 - hint about the error
	 * @var $code	 - defaults to 400 (Bad Request)
	*/
	protected function errorResponse($message, $hint = '', $code = 400)
	{
		// verify that the http status code is valid
		if (!($code >= 300 && $code <= 500)) {
			$code = 400;
		}

		// return the cleaned version of data
		return response()->json([
			'code'		=> $code,
			'message'	=> $message,
			'error'		=> $hint,
		], $code);
	}
}
