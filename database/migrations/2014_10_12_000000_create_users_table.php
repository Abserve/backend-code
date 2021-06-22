<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->bigInteger('phone');
            $table->string('role');
            $table->bigInteger("cin");
            $table->string("adresse");
            $table->string('photo')->default('');
            $table->date("d_naissance")->nullable();
            $table->string('embSet',5000)->nullable();
            $table->string("qulification")->nullable();
            $table->date("embauche_date")->nullable();
            $table->boolean("indirect")->nullable();
            $table->boolean("actif")->default(1)->nullable();
            $table->date("date_inactivite")->nullable();
            $table->string('tva_code')->nullable();
            $table->string('email_accounting')->nullable();
            $table->string('email_demandeur')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
