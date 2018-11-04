<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compCode',false)->unique();
            $table->string('compName',200);
            $table->string('city',200);
            $table->string('state',200);
            $table->string('postCode',200);
            $table->string('country',100);
            $table->string('phoneNo',200);
            $table->string('email',200)->unique();
            $table->char('currency',3)->nullable();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compCode',false)->default(0);
            $table->string('name',60);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role',20);
            $table->timestamp('lastLogin')->nullable();
            $table->ipAddress('visitor')->nullable();
            $table->integer('wrongPassCount',false)->default(0);
            $table->boolean('status')->default(1);
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('compCode')
                ->references('compCode')->on('companies')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
        Schema::dropIfExists('companies');
    }
}
