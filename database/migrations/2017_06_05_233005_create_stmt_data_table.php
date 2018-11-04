<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStmtDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stmt_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compCode',false);
            $table->string('fileNo',4);
            $table->integer('lineNo',false)->default(0);
            $table->integer('textPosition',false)->default(0);
            $table->integer('font',false)->default(10);
            $table->string('texts',40);
            $table->string('accType',1)->nullable();
            $table->string('balType',3)->nullable();
            $table->string('note',2)->nullable();
            $table->string('ac11',9)->nullable();
            $table->string('ac12',9)->nullable();
            $table->string('ac21',9)->nullable();
            $table->string('ac22',9)->nullable();
            $table->integer('figrPosition',false)->nullable()->default(0);
            $table->string('subTotal',1)->nullable();
            $table->string('pFormula',51)->nullable();
            $table->decimal('rangeVal1',15,2)->nullable()->defalut(0);
            $table->decimal('prntVal1',15,2)->nullable()->default(0);
            $table->decimal('rangeVal2',15,2)->nullable()->default(0);
            $table->decimal('prntVal2',15,2)->nullable()->default(0);
            $table->decimal('rangeVal3',15,2)->nullable()->default(0);
            $table->decimal('prntVal3',15,2)->nullable()->default(0);
            $table->decimal('prntVal',15,2)->nullable()->default(0);
            $table->decimal('pcnt',15,2)->nullable()->default(0);
            $table->integer('user_id')->unsigned();
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->foreign('compCode')
                ->references('compCode')->on('companies')
                ->onDelete('RESTRICT');
        });

        Schema::table('stmt_data', function($table) {
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
        Schema::dropIfExists('stmt_data');
    }
}
