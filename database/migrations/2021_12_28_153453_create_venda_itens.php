<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendaItens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venda_itens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loja_id');
            $table->unsignedBigInteger('produto_id')->nullable();
            $table->unsignedBigInteger('estoque_id')->nullable();
            $table->unsignedBigInteger('venda_id')->nullable();
            $table->string('alltech_id');
           // $table->string('nota')->nullable();;
            $table->decimal('custo');
            $table->decimal('valor');
            $table->datetime('data')->nullable();
            $table->decimal('quantidade');
            $table->timestamps();

            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');

            $table->foreign('produto_id')->references('id')->on('produtos')->onDelete('cascade');
            $table->foreign('estoque_id')->references('id')->on('estoques')->onDelete('cascade');

            $table->foreign('venda_id')->references('id')->on('vendas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('venda_itens', function (Blueprint $table) {
            $table->dropForeign('venda_itens_loja_id_foreign');
            $table->dropForeign('venda_itens_produto_id_foreign');
            $table->dropForeign('venda_itens_venda_id_foreign');

            $table->dropColumn('loja_id');
            $table->dropColumn('produto_id');
            $table->dropColumn('venda_id');
        });

        Schema::dropIfExists('venda_itens');
    }
}
