<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaixasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caixas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loja_id');
            $table->string('alltech_id')->nullable();
            $table->string('historico')->nullable();
            $table->decimal('valor')->nullable();
            $table->datetime('data')->nullable();
            $table->string('parcela')->nullable();
            $table->string('documento')->nullable();
            $table->string("controle")->nullable();
            $table->string('movimento')->nullable();
            $table->string('especie')->nullable();
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
        Schema::table('caixas', function (Blueprint $table) {        
            $table->dropForeign('caixas_loja_id_foreign');
            $table->dropColumn('loja_id');

        });
        Schema::dropIfExists('caixas');
    }
}
