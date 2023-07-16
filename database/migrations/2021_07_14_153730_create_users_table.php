<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('name', 191);
			$table->string('username', 191);
			$table->string('email', 191);
			$table->dateTime('email_verified_at')->nullable();
			$table->string('password', 191);
			$table->integer('status');
			$table->dateTime('last_login')->nullable();
			$table->string('remember_token', 100)->nullable();
			$table->timestamps();
			$table->integer('assign_employee')->nullable();
			$table->bigInteger('employee_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
