<?php
/**
 *  @author: $rachow
 *  @copyright: Coinhoppa
 *
 *  Ability to cast a serialization attribute.
 */

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Serialize implements CastsAttributes
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
        	return unserialize($value);
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
        	return serialize($value);
    	}
}
