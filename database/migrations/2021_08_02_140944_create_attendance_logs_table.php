<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('attendance_id')->nullable();
            $table->bigInteger('employee_id');
            $table->enum('type', ['in', 'out']);
            $table->timestamp('attended_at');
            $table->timestamps();

            // $table->string('serial_number', 191)->nullable();
            // $table->string('device_name', 191)->nullable();
            // $table->string('attendance_area', 191)->nullable();
            // $table->string('type', 191);
            // $table->dateTime('attendance_date');
            // $table->bigInteger('workingtime_id')->nullable();
            // $table->float('workingtime', 10, 0)->nullable();
            // $table->float('overtime', 10, 0)->nullable();
            // $table->dateTime('in')->nullable();
            // $table->dateTime('out')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_logs');
    }
}
