<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiscalPeriodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fiscal_period', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compCode',false);
            $table->string('FiscalYear',9);
            $table->integer('year',false);
            $table->integer('fpNo',false);
            $table->integer('monthSl',false);
            $table->string('monthName',9);
            $table->date('startDate');
            $table->date('endDate');
            $table->boolean('status');
            $table->boolean('depriciation');
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
        Schema::dropIfExists('fiscal_period');
    }
}
