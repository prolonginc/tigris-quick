<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
                $table->string('order_number')->nullable();
                $table->string('total_price')->nullable();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('pickup_info')->nullable();
                $table->string('pickup_time')->nullable();
                $table->string('status')->default('pending');
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
};
