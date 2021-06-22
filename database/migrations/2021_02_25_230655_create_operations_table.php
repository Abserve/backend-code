<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->date("date_operation");
            $table->integer("qte_controlée");
            $table->integer("qte_ok");
            $table->integer("qte_notOk");
            $table->text("détail_defaut");
            $table->text("buch_num");
            $table->text("Delivery_num");
            $table->text("remarque");
            $table->unsignedBigInteger('mission_id');
            $table->foreign('mission_id')->references('id')->on('missions')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('operations');
    }
}
