<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refobs', function (Blueprint $table) {
            $table->id();
            $table->string('ref_obs');
            $table->date('date_refobs');
            $table->text('comment');



            $table->unsignedBigInteger('refobs_id');
            $table->foreign('refobs_id')->references('id')->on('articles')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('refobs');
    }
}
