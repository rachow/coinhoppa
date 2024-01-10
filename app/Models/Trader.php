<?php
/**
 *	@author: $rachow
 *	@copyright: Coinhoppa
 *
 *	Trader ORM for trading users.
 *
*/

namespace App\Models;

use App\Casts\Json;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Trader extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable;

    /**
     * table name.
     */
    protected $table = 'traders';

    /**
     * mass fillables.
     */
	protected $fillable = [
		'firstname',
		'lastname',
		'email',
		'password',
	];

    /**
     * hidden attributes.
     */
	protected $hidden = [
		'password',
		'remember_token',
	];

    /**
     * attributes casting.
     */
	protected $casts = [
		'email_verified_at' => 'datetime',
		'storable' => Json::class,
		'created_at' => 'datetime:d-m-Y H:i',
		'updated_at' => 'datetime:d-m-Y H:i',
    ];
}
