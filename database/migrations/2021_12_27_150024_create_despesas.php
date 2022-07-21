<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDespesas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('despesas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loja_id');
            $table->string('alltech_id');
            $table->decimal('valor')->nullable();
            $table->decimal('valor_aberto')->nullable();
            $table->date('emissao')->nullable();
            $table->date('vencimento')->nullable();
            $table->string('parcela')->nullable();
            $table->string('posicao')->nullable();
            $table->string('nota')->nullable();
            $table->unsignedBigInteger('fornecedor_id')->nullable();
            $table->timestamps();

            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');

            $table->foreign('fornecedor_id')->references('id')->on('fornecedors')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('despesas', function (Blueprint $table) {
            $table->dropForeign('despesas_loja_id_foreign');
            $table->dropForeign('despesas_fornecedor_id_foreign');

            $table->dropColumn('loja_id');
            $table->dropColumn('fornecedor_id');
        });
        Schema::dropIfExists('despesas');
    }
}
