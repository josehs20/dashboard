<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarrinhosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carrinhos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('data')->nullable();
            $table->string('n_pedido')->nullable();
            $table->decimal('desconto_qtd')->nullable();
            $table->string('tp_desconto')->nullable();
            $table->decimal('valor_desconto')->nullable();
            $table->decimal('valor_bruto')->nullable();
            $table->decimal('total')->nullable();
            $table->string('tipo_pagamento')->nullable();
            $table->string('forma_pagamento')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->integer('parcelas')->nullable();
            $table->string('tp_desconto_sb_venda')->nullable();
            $table->decimal('valor_desconto_sb_venda')->nullable();
            $table->decimal('desconto_qtd_sb_venda')->nullable();
            $table->decimal('valor_entrada')->nullable();   

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
           // $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carrinhos', function (Blueprint $table) {
            $table->dropForeign('carrinhos_cliente_id_foreign');
            $table->dropColumn('cliente_id');
        });
        Schema::dropIfExists('carrinhos');
    }
}
