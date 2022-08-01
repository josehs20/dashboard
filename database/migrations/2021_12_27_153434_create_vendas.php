<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loja_id');
            $table->string('alltech_id');
            $table->string('vendedor')->nullable();
            $table->decimal('descAcres')->nullable();
            $table->date('data')->nullable();
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
        Schema::table('vendas', function (Blueprint $table) {
            $table->dropForeign('vendas_loja_id_foreign');
            $table->dropColumn('loja_id');
        });
        Schema::dropIfExists('vendas');
    }
}
