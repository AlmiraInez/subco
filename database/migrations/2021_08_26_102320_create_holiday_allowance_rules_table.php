<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHolidayAllowanceRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holiday_allowance_rules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('allowance_id')->nullable();
            $table->integer('first')->nullable();
            $table->integer('until')->nullable();
            $table->float('value')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();

            $table->foreign('allowance_id')->references('id')->on('allowances')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('holiday_allowance_rules');
    }
}
