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
            $table->unsignedInteger('voti_12');
            $table->unsignedInteger('voti_19');
            $table->unsignedInteger('voti');
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
    }
};
