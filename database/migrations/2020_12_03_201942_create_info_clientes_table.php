<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfoClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_clientes', function (Blueprint $table) {
            $table->id();   
            $table->string('observacao')->nullable();
            $table->string('data')->nullable();            
            $table->unsignedBigInteger('cliente_id');
            
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('info_clientes', function (Blueprint $table) {
            $table->dropForeign('info_clientes_cliente_id_foreign');
            $table->dropColumn('cliente_id');
        });
        Schema::dropIfExists('info_clientes');
    }
}
