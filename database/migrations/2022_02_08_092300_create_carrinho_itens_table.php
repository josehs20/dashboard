<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarrinhoItensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carrinho_itens', function (Blueprint $table) {
            $table->id();
            $table->decimal('quantidade')->nullable();
            $table->decimal('preco')->nullable();
            $table->string('tipo_desconto')->nullable();
            $table->decimal('qtd_desconto')->nullable();
            $table->decimal('valor_desconto')->nullable();
            $table->decimal('valor')->nullable();
            $table->unsignedBigInteger('produto_id');
            $table->unsignedBigInteger('carrinho_id');
            $table->unsignedBigInteger('i_grade_id')->nullable();
            
            $table->timestamps();
            
            $table->foreign('i_grade_id')->references('id')->on('igrades')->onDelete('cascade');
            $table->foreign('produto_id')->references('id')->on('produtos')->onDelete('cascade');
            $table->foreign('carrinho_id')->references('id')->on('carrinhos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carrinho_itens', function (Blueprint $table) {
            $table->dropForeign('carrinho_itens_i_grade_id_foreign');

            $table->dropColumn('i_grade_id');
        });
        Schema::dropIfExists('carrinho_itens');
    }
}
