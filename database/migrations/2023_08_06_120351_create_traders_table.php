<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('traders', function (Blueprint $table) {
            $table->id();
            $table->string('firstname', 100);
            $table->string('lastname', 100);
            $table->string('email', 200);
            $table->string('password', 255);
    	    $table->string('remember_token', 100)->nullable();
            $table->string('avatar', 255)->nullable();
     	    $table->tinyInteger('active')->nullable()->default('0');
    	    $table->timestamp('activation_date')->nullable();
            $table->tinyInteger('banned')->nullable()->default('0');
            $table->datetime('banned_until')->nullable();
    	    $table->timestamp('password_changed_at')->nullable();
    	    $table->string('last_login_ip', 16)->nullable();
			$table->timestamp('last_login_at')->nullable();
			$table->timestamp('last_activity')->nullable();
            $table->timestamps();
            $table->unique(["email"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('traders');
    }
}
