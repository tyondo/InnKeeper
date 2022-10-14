<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('innkeeper')->create('organization_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_uid');
            $table->string('api_token', 60)->unique()->nullable();
            $table->string('first_name');
            $table->string('user_status')->nullable();
            $table->string('last_name');
            $table->string('display_name');
            $table->bigInteger('mobile_number')->index();
            $table->unsignedInteger('organization_id')->index();
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('organizations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('innkeeper')->dropIfExists('organization_users');
    }
}
