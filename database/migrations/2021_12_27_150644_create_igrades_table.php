<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIgradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('igrades', function (Blueprint $table) {
            $table->id();
            $table->string('alltech_id')->nullable();
            $table->string('id_grade_alltech_id')->nullable();
            $table->unsignedBigInteger('loja_id');
            $table->string('tam')->nullable();
            $table->decimal('fator')->nullable();
            $table->string('tipo')->nullable();
            $table->unsignedBigInteger('grade_id')->nullable();
            

            $table->timestamps();

            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');
            $table->foreign('grade_id')->references('id')->on('grades')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('igrades', function (Blueprint $table) {
            $table->dropForeign('igrades_loja_id_foreign');
            $table->dropForeign('igrades_grade_id_foreign');

            $table->dropColumn('loja_id');
            $table->dropColumn('grade_id');
        });
        Schema::dropIfExists('igrades');
    }
}
