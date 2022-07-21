<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnderecosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enderecos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cidade_ibge_id')->nullable();
            $table->integer('cep')->nullable();
            $table->string('bairro')->nullable();
            $table->string('rua')->nullable();
            $table->integer('numero')->nullable();
            $table->string('compto')->nullable();
            $table->string('tipo')->nullable();
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->timestamps();

            $table->foreign('cidade_ibge_id')->references('id')->on('cidades_ibge')->onDelete('cascade');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enderecos', function (Blueprint $table) {
            $table->dropForeign('enderecos_cidade_ibge_id_foreign');
            $table->dropColumn('cidade_ibge_id');
            $table->dropForeign('enderecos_cliente_id_foreign');
            $table->dropColumn('cliente_id');
        });
        Schema::dropIfExists('enderecos');
    }
}
