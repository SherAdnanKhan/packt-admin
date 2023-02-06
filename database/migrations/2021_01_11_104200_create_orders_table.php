<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_ref');
            $table->string('order_id')->nullable(); //MS order uuid
            $table->string('user_id'); //Customer uuid
            $table->string('created_by'); //Admin User_id
            $table->integer('invoiceNumber');
            $table->enum('printingHouse', ['PCKTUK', 'PCKTAU', 'PCKTUS', 'PCKTIN'])->nullable();
            $table->string('customerGroup')->nullable();
            $table->float('price_paid');
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
        Schema::dropIfExists('orders');
    }
}
