<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('quickbooks_tokens', function (Blueprint $table) {
//
//            $table->bigIncrements('id');
//            $table->unsignedBigInteger('user_id');
//            $table->unsignedBigInteger('realm_id');
//            $table->longtext('access_token');
//            $table->datetime('access_token_expires_at');
//            $table->string('refresh_token');
//            $table->datetime('refresh_token_expires_at');
//
//            $table->timestamps();
//
//            $table->foreign('user_id')
//                ->references('id')
//                ->on('users')
//                ->onDelete('cascade');
//        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
