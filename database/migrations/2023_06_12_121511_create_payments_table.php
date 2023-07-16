<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->nullable();
            $table->string('code_system')->nullable();
            $table->date('payment_date');
            $table->float('invoice_amount', 10, 0);
            $table->float('discount', 10, 0)->nullable();
            $table->float('payment_amount', 10, 0);
            $table->string('payment_img');
            $table->integer('stat_approval');
            $table->date('approval_date');
            $table->unsignedBigInteger('room_category');
            $table->unsignedBigInteger('trans_id');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('site_id');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
