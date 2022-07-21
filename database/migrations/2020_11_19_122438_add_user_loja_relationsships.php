<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserLojaRelationsships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loja_user', function (Blueprint $table) {
            $table->unsignedBigInteger('loja_id');
            $table->unsignedBigInteger('user_id');
            $table->string('drop', 1)->nullable();

            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loja_user', function (Blueprint $table) {        
            $table->dropForeign('loja_user_user_id_foreign');
            $table->dropForeign('loja_user_loja_id_foreign');

        });

        Schema::table('loja_user', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('loja_id');
        });

        Schema::dropIfExists('loja_user');
    }
}
