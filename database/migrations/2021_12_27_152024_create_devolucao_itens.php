<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevolucaoItens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devolucao_itens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loja_id');
            $table->unsignedBigInteger('produto_id')->nullable();
            $table->unsignedBigInteger('estoque_id')->nullable();
            $table->unsignedBigInteger('devolucao_id')->nullable();
           // $table->string('nota')->nullable();;
            $table->string('alltech_id');
            $table->decimal('quantidade');
            $table->decimal('custo');
            $table->decimal('valor');
            $table->date('data')->nullable();            
            $table->timestamps();

            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');

            $table->foreign('produto_id')->references('id')->on('estoques')->onDelete('cascade');
            
            $table->foreign('estoque_id')->references('id')->on('estoques')->onDelete('cascade');

            $table->foreign('devolucao_id')->references('id')->on('devolucoes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devolucao_itens', function (Blueprint $table) {
            $table->dropForeign('devolucao_itens_loja_id_foreign');
            $table->dropForeign('devolucao_itens_produto_id_foreign');
            $table->dropForeign('devolucao_itens_devolucao_id_foreign');

            $table->dropColumn('loja_id');
            $table->dropColumn('produto_id');
            $table->dropColumn('devolucao_id');
        });
        Schema::dropIfExists('devolucao_itens');
    }
}
