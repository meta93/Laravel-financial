<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepreciationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('depreciation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compCode',false);
            $table->string('accNo',8);
            $table->integer('fpNo',false);
            $table->date('opnDate');
            $table->date('endDate');
            $table->decimal('openBall',15,2);
            $table->decimal('Addition',15,2);
            $table->decimal('totalVal',15,2);
            $table->decimal('depRate',5,2);
            $table->decimal('deprAmt',15,2);
            $table->decimal('finalval',15,2);
            $table->boolean('postingStatus');
            $table->date('postDate')->nullable();
            $table->string('contraAcc',8);
            $table->string('userCreated',20);
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
        Schema::dropIfExists('depreciation');
    }
}
