<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevolucoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devolucoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loja_id');
            $table->string('alltech_id');
            //$table->string('nvenda')->nullable();
            $table->date('data');
            $table->decimal('total')->nullable();
            $table->string('tipo')->nullable();
            $table->date('data_cancelamento')->nullable();            
            $table->timestamps();

            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devolucoes', function (Blueprint $table) {
            $table->dropForeign('devolucoes_loja_id_foreign');
            $table->dropColumn('loja_id');
        });
        Schema::dropIfExists('devolucoes');
    }
}
