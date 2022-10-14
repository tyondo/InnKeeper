<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('innkeeper')->create('organization_archives', function (Blueprint $table) {
            $table->increments('id');
            $table->string('organization');
            $table->unsignedInteger('user_id')->index();
            $table->json('organization_archive_details');
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
        Schema::connection('innkeeper')->dropIfExists('organization_archives');
    }
}
