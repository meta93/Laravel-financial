<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBasicPropertyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('basic_properties', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compCode',false)->unique();
            $table->tinyInteger('project')->default(0);
            $table->tinyInteger('inventory')->default(0);
            $table->integer('cash',false)->default(101);
            $table->integer('bank',false)->default(102);
            $table->integer('sales',false)->default(301);
            $table->integer('purchase',false)->default(401);
            $table->char('currency',3)->nullable();
            $table->boolean('posted')->default(false);
            $table->date('fpStart');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
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
        Schema::dropIfExists('basic_properties');
    }
}
