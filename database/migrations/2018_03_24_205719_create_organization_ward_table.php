<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationWardTable extends Migration
{
    public function up()
    {
        Schema::create('organization_ward', function (Blueprint $table) {
            $table->unsignedInteger('organization_id');
            $table->unsignedInteger('ward_id');

            $table->foreign('organization_id')
                ->references('id')->on('organizations')
                ->onDelete('cascade');

            $table->foreign('ward_id')
                ->references('id')->on('wards')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('organization_ward');
    }
}
