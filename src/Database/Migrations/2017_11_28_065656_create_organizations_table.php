<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('innkeeper')->create('organizations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('organization_account')->index();
            $table->enum('management_status', ['new_organization', 'manage_organization'])->default('new_organization');
            $table->string('organization_uid');
            $table->string('name',255);
            $table->string('slug', 255);
            $table->string('domain')->unique()->nullable()->index();
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
        Schema::connection('innkeeper')->dropIfExists('organizations');
    }
}
