<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('ref_abs');
            $table->string('ref_client');
            $table->string('designation');
            $table->string('cmj');
            $table->string('type_op');
            $table->string('cadence_dti');
            $table->string('cadence_abs');
            $table->unsignedBigInteger('op_id');
            $table->foreign('op_id')->references('id')->on('operations')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('articles');
    }
}
