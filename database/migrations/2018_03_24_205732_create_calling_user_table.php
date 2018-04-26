<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCallingUserTable extends Migration
{
    public function up()
    {
        Schema::create('calling_user', function (Blueprint $table) {
            $table->unsignedInteger('calling_id');
            $table->unsignedInteger('user_id');
            $table->string('status')->default('indicated');

            $table->dateTime('supported_at')->nullable();
            $table->dateTime('released_at')->nullable();
            $table->dateTime('designated_at')->nullable();

            $table->timestamps();

            $table->foreign('calling_id')
                ->references('id')->on('callings')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('calling_user');
    }
}
