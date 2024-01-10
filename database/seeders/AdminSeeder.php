<?php
/**
 *	@author: $rachow
 *	@copyright: Coinhoppa
 *
 *	Admin table seeder.
 *
*/

namespace Database\Seeders;

use App\Models\Admin;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
	protected $table = 'admins';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    // $this->truncate();

		Admin::create([
			'firstname' 	=> 'Admin',
			'lastname'  	=> 'Admin',
			'email'  	=> 'admin101@coinhoppa.co',
			'password'	=> Hash::make('123456!!$.'),
			'admin_level'	=> '3',
        ]);

        // add more.
	}

    /**
     * Run a table truncate func.
     *
     * @return void
     */
	private function truncate()
	{
		DB::table($this->table)->truncate();
		return true;
	}
}
