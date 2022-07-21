<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loja_id');
            $table->string('alltech_id')->nullable();
            $table->string('refcia')->nullable();
            $table->string('nome')->nullable();
            $table->decimal('custo')->nullable();
            $table->decimal('preco')->nullable();
            $table->string('un')->nullable();
            $table->string('situacao')->nullable();
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
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropForeign('produtos_loja_id_foreign');
            $table->dropColumn('loja_id');
        });
        Schema::dropIfExists('produtos');
    }
}
