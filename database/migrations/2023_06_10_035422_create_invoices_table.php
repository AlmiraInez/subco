<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->nullable();
            $table->string('code_system')->nullable();
            $table->date('invoice_date');
            $table->float('price', 10, 0);
            $table->float('discount', 10, 0)->nullable();
            $table->float('amount', 10, 0);
            $table->integer('payment_status');
            $table->date('payment_date')->nullable();
            $table->integer('stat_approval');
            $table->integer('invoice_number')->nullable();
            $table->unsignedBigInteger('room_category');
            $table->unsignedBigInteger('trans_id');
            $table->unsignedBigInteger('tenant_id');
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
        Schema::dropIfExists('invoices');
    }
}
