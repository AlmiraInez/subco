<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employee_id');
            $table->timestamp('in_at');
            $table->timestamp('out_at')->nullable();
            $table->date('attended_at');
            $table->timestamps();

            // $table->string('attendance_code', 100)->nullable();
            // $table->string('status');
            
            // $table->foreign('employee_id')->references('id')->on('employees')->onDelete('restrict');
            // $table->foreign('attendance_code_id')->references('id')->on('attendance_codes')->onDelete('restrict');
            // $table->date('attendance_date')->nullable();
            // $table->timestamp('attendance_in')->nullable();
            // $table->dateTime('attendance_out')->nullable();
            // $table->integer('status')->default(0);
            // $table->float('adj_working_time', 10, 0)->nullable();
            // $table->float('adj_over_time', 10, 0)->nullable();
            // $table->text('note')->nullable();
            // $table->bigInteger('workingtime_id')->nullable();
            // $table->string('day', 191)->nullable();
            // $table->bigInteger('overtime_scheme_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
