<?php
/**
 *	@author: $rachow
 *	@copyright: Coinhoppa
 *
 *	Admin user model
 *
*/

namespace App\Models;

use Cache;
use Carbon\Carbon;
use App\Traits\AdminUtils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory, AdminUtils;

	/**
	 * table name
	*/
	protected $table = 'admins';

	/**
	 * mass fillables.
	*/
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'avatar',
        'admin_level',
        'active',
        'activation_date',
        'banned',
        'banned_until',
        'last_login_at',
        'last_login_ip',
        'last_activity'
    ];

	/**
	 * hidden attributes.
	*/
	protected $hidden = [
        'password',
        'remember_token',
	];	

    /**
     * additional attributes.
     */
	protected $appends = [
		'useronline',
	];

    /**
     * attributes castings.
     */
	protected $casts = [
		'created_at' => 'datetime:d-m-Y H:i',
		'updated_at' => 'datetime:d-m-Y H:i',
	];

	/*
	* Grab the user online attribue.
	* @param  none
	* @return string
	*/
	public function getUserOnlineAttribute(): string
	{
        // do we add the app ID too - UUID
        // e.g. admin-online_65a05896-345f-4b05-83e1-51cf7b9362ce

		if (Cache::has('admin-online-' . $this->id)) {
            return 'online';
        }
		return '';
	}

	/*
     * Confirm if the admin user is online.
     *
	 * @param  none
	 * @return boolean
	 */
	public function isOnline()
	{
		$app_id = config('app.app_id', '65a05896-345f-4b05-83e1-51cf7b9362ce');
		return Cache::has('admin-online-' . $this->id);
	}

    /**
     * Set the administrator online with expiry.
     *
	 * @param  $expiry
	 * @return void
	 */
	public function setOnline($expires = null)
	{
		$app_id = config('app.app_id', '65a05896-345f-4b05-83e1-51cf7b9362ce');
		$expires = ($expires !== null) ? $expires : Carbon::now()->addMinutes(5);
		Cache::put('admin-online-' . $this->id, true, $expires);
	}
}
