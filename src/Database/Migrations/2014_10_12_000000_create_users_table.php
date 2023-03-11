<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('innkeeper')->create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('display_name');
            $table->string('visibility')->default('hidden');
            $table->string('country_code', 4)->nullable();
            $table->bigInteger('mobile_number')->index();
            $table->integer('organization_id')->nullable()->index();
            $table->dateTime('last_login_at')->nullable();
            $table->ipAddress('last_login_ip')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
        DB::connection('innkeeper')->table('users')->insert([
            'first_name' => 'Test',
            'last_name' => 'User',
            'display_name' => 'Test User',
            'mobile_number' => 0700225544,
            'created_at' => Carbon::now(),
            'organization_id' => 1,
            'email' => 'info@tyondo.com',
            'password' => Hash::make('2020@Tyondo') ,
        ]);
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
};
