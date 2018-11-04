<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apps_countries', function (Blueprint $table) {
            $table->increments('id');
            $table->char('countryCodeA',2)->unique();
            $table->char('countryCodeN',3)->unique();
            $table->string('countryName',60);
            $table->string('nickName',60)->nullable();
            $table->char('currencyCodeN',4)->nullable();
            $table->char('currencyCodeA',3)->nullable();
            $table->string('currency',60)->nullable();
            $table->string('currencySymble',4)->nullable();
            $table->string('phoneCode',10)->nullable();
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apps_countries');
    }
}
