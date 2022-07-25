<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstoques extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estoques', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loja_id');
            $table->string('alltech_id')->nullable();
            $table->string('codbar')->nullable();
            $table->unsignedBigInteger('i_grade_id')->nullable();
            $table->string('tam')->nullable();
            $table->string('cor')->nullable();
            $table->string('produto_id')->nullable();
            $table->decimal('saldo')->nullable();
            $table->timestamps();

            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');

            $table->foreign('i_grade_id')->references('id')->on('igrades');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estoques', function (Blueprint $table) {
            $table->dropForeign('estoques_loja_id_foreign');
            $table->dropColumn('loja_id');

            $table->dropForeign('estoques_i_grade_id_foreign');
            $table->dropColumn('i_grade_id');
        });
        Schema::dropIfExists('estoques');
    }
}
