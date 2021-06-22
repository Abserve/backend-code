<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatepointagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datepointages', function (Blueprint $table) {
            $table->id();
            $table->date('date_pointage');
            $table->time('heur_deb')->nullable();
            $table->time('heure_fin')->nullable();
            $table->boolean('absence')->nullable();
            $table->string('mostif_absence')->nullable();
            $table->string('majoration')->nullable();
            $table->unsignedBigInteger('operationn_id');
            $table->foreign('operationn_id')->references('id')->on('operations')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('datepointages');
    }
}
