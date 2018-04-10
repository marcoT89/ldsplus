<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCallingsTable extends Migration
{
    public function up()
    {
        Schema::create('callings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('organization_id');
            $table->string('gender')->default('both');
            $table->timestamps();

            $table->foreign('organization_id')
                ->references('id')->on('organizations')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('callings');
    }
}
