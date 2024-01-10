<?php
/**	
 *	@author: $rahel
 *	@copyright: 2023
 *
 *	Apps response service provider
 *
*/

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseServiceProvider extends ServiceProvider
{
	/**
	 * register any logics on application
	 * booting process.
	*/
	public function boot()
	{
		// output clean JSON removing any DB output data cleanup
		Response::macro('clean_json', function($data, $code = 200, $pretty = false){
			$json = ($pretty === false) ? json_encode($data) : json_encode($data, JSON_PRETTY_PRINT);
			$json = str_replace(':null', ':""', $json);
			return response($json, $code)
				->header('Content-Type', 'application/json');
		});
	}
}


