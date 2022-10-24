<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('risultati_camera', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lista_id')->references('id')->on('liste');
            $table->unsignedInteger('voti');
            $table->unsignedDecimal('percentuale', 5, 2);
        });

        Schema::create('risultati_senato', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lista_id')->references('id')->on('liste');
            $table->unsignedInteger('voti');
            $table->unsignedDecimal('percentuale', 5, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('risultati_camera');
        Schema::dropIfExists('risultati_senato');
    }
};
