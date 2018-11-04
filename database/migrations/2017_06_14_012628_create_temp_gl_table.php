<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempGlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_gl', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compCode',false);
            $table->string('jCode',3)->nullable();
            $table->string('projCode',6)->nullable();
            $table->string('refNo',12)->nullable();
            $table->string('chequeNo',20)->nullable();
            $table->date('transDate');
            $table->string('voucherNo',6);
            $table->string('accNo',8)->nullable();
            $table->string('contraNo',8)->nullable();
            $table->decimal('drAmt',15,2)->default(0);
            $table->decimal('crAmt',15,2)->default(0);
            $table->decimal('transAmt',15,2)->default(0);
            $table->string('currCode',3)->nullable();
            $table->string('transDesc1',60)->nullable();
            $table->string('transDesc2',60)->nullable();
            $table->integer('user_id')->unsigned();
            $table->timestamps();
            $table->foreign('compCode')
                ->references('compCode')->on('companies')
                ->onDelete('cascade');
        });

        Schema::table('temp_gl', function($table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_gl');
    }
}
