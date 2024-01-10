<?php
/**
 * 	@author: $rachow
 * 	@copyright: Coinhoppa
 *
 * 	Admin re-usable routines.
*/

namespace App\Traits;

use Auth;
use App\Models\Admin;

trait AdminUtils
{
	protected const ROOT_ADMIN = 101;

	protected const SUPER_ADMIN = 3;

	protected const BASIC_ADMIN = 2;

	/**
	 * For root priviledges to the system.
	 *
	 * @param App\Models\Admin [optional]
	 * @return boolean
	*/
	protected function isRootAdmin($admin = null): bool
	{
	    $admin_id = ($admin instanceof App\Models\Admin) ? $admin->id : auth('admins')->user()->id;

	    return (bool) $admin_id == self::ROOT_ADMIN;
	}

    /**
     * For obtaining the global root admin user id.
     */
	protected function getRootAdminId(): int 
	{
	    return (int) self::ROOT_ADMIN;
	}
}
