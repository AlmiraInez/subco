<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmployeesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('employees', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('name', 191);
			$table->string('phone', 191);
			$table->string('gender', 191);
			$table->bigInteger('place_of_birth');
			$table->date('birth_date');
			$table->string('address', 191);
			$table->timestamps();
			$table->string('nid', 200)->nullable();
			$table->string('email', 200);
			$table->integer('title_id');
			$table->integer('department_id');
			$table->integer('grade_id');
			$table->string('nik', 191);
			$table->string('npwp', 191);
			$table->integer('province_id');
			$table->string('account_bank', 191);
			$table->string('account_no', 191);
			$table->string('account_name', 191);
			$table->string('emergency_contact_no', 191);
			$table->string('emergency_contact_name', 191);
			$table->string('working_time_type', 191);
			$table->integer('status');
			$table->text('notes')->nullable();
			$table->string('photo', 191);
			$table->integer('working_time')->nullable();
			$table->date('join_date')->nullable();
			$table->date('resign_date')->nullable();
			$table->string('bpjs_tenaga_kerja', 191)->nullable();
			$table->string('ptkp', 191)->nullable();
			$table->string('tax_calculation', 191)->nullable();
			$table->string('mother_name', 191)->nullable();
			$table->bigInteger('region_id')->nullable();
			$table->bigInteger('outsourcing_id')->nullable();
			$table->bigInteger('calendar_id')->nullable();
			$table->bigInteger('workgroup_id')->nullable();
			$table->string('overtime', 191)->nullable();
			$table->string('timeout', 191)->nullable();
			$table->bigInteger('user_id')->nullable();
			$table->string('setup_user', 191)->nullable();
			$table->bigInteger('role_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('employees');
	}

}
