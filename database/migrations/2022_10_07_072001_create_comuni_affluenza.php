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
        Schema::create('comuni_affluenze', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comune_id')->references('id')->on('comuni');
            $table->unsignedInteger('elettori');
            $table->unsignedInteger('elettori_m');
            $table->unsignedInteger('elettori_f');
            $table->unsignedDecimal('percentuale_12');
            $table->unsignedInteger('voti_12');
            $table->unsignedInteger('voti_12_m');
            $table->unsignedInteger('voti_12_f');
            $table->unsignedDecimal('percentuale_19');
            $table->unsignedInteger('voti_19');
            $table->unsignedInteger('voti_19_m');
            $table->unsignedInteger('voti_19_f');
            $table->unsignedDecimal('percentuale_23');
            $table->unsignedInteger('voti_23');
            $table->unsignedInteger('voti_23_m');
            $table->unsignedInteger('voti_23_f');
        });

        Schema::table('comuni', function (Blueprint $table) {
            $table->string('codice_elettorale');
            $table->string('codice_istat');
            $table->string('codice_belfiore');
            $table->string('codice_interno');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comuni_affluenze');

        Schema::table('comuni', function (Blueprint $table) {
            $table->dropColumn('codice_elettorale');
            $table->dropColumn('codice_istat');
            $table->dropColumn('codice_belfiore');
            $table->dropColumn('codice_interno');
        });
    }
};
