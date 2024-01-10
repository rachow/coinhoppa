<?php
/**
 *	@author: $rachow
 *	@copyright: Coinhoppa
 *	
 *	The power of Casting
 *	  - Model needs to perform manipulation for a specific
 *	    format to an attribute or bunch of them.
*/

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Json implements CastsAttributes
{
    	/**
     	* Cast the given value.
     	*
     	* @param  \Illuminate\Database\Eloquent\Model  $model
     	* @param  string  $key
     	* @param  mixed  $value
     	* @param  array  $attributes
     	* @return mixed
     	*/
    	public function get($model, string $key, $value, array $attributes)
    	{
        	return json_decode($value, true);
    	}

    	/**
     	* Prepare the given value for storage.
     	*
     	* @param  \Illuminate\Database\Eloquent\Model  $model
     	* @param  string  $key
     	* @param  mixed  $value
     	* @param  array  $attributes
     	* @return mixed
     	*/
    	public function set($model, string $key, $value, array $attributes)
    	{
        	return json_encode($value);
    	}
}
