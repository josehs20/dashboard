<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceitas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receitas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loja_id');
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->string('alltech_id');
            $table->decimal('valor')->nullable();
            $table->decimal('valor_aberto')->nullable();
            $table->date('emissao')->nullable();
            $table->date('vencimento')->nullable();
            $table->string('parcela')->nullable();
            $table->string('posicao')->nullable();
            $table->string('nota')->nullable();
            $table->timestamps();

            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');

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
        Schema::table('receitas', function (Blueprint $table) {
            $table->dropForeign('receitas_loja_id_foreign');
            $table->dropForeign('receitas_cliente_id_foreign');

            $table->dropColumn('loja_id');
            $table->dropColumn('cliente_id');
        });
        Schema::dropIfExists('receitas');
    }
}
