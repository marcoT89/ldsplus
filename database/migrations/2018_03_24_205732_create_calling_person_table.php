<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCallingPersonTable extends Migration
{
    public function up()
    {
        Schema::create('calling_person', function (Blueprint $table) {
            $table->unsignedInteger('calling_id');
            $table->unsignedInteger('person_id');
            $table->string('status')->default('call');
            $table->date('released_at')->nullable();
            $table->date('called_at')->nullable();
            $table->timestamps();

            $table->foreign('calling_id')
                ->references('id')->on('callings')
                ->onDelete('cascade');

            $table->foreign('person_id')
                ->references('id')->on('people')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('calling_person');
    }
}
