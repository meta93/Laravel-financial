<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compCode',false);
            $table->string('period',8)->nullable();
            $table->string('jCode',3)->nullable();
            $table->string('projCode',6)->nullable();
            $table->integer('fpNo',false)->default(0);
            $table->string('refNo',12)->nullable();
            $table->string('chequeNo',20)->nullable();
            $table->string('costCenter',4)->nullable();
            $table->string('transId',18);
            $table->string('transGrpId',18);
            $table->date('transDate');
            $table->string('voucherNo',6);
            $table->string('accDr',8)->nullable();
            $table->string('accCr',8)->nullable();
            $table->decimal('drAmt',15,2)->default(0);
            $table->decimal('crAmt',15,2)->default(0);
            $table->decimal('transAmt',15,2)->default(0);
            $table->string('currCode',3)->nullable();
            $table->decimal('exchRate',8,2)->nullable();
            $table->string('fiscalYear',9)->nullable();
            $table->string('transDesc1',60)->nullable();
            $table->string('transDesc2',60)->nullable();
            $table->boolean('postFlag')->nullable();
            $table->date('postDate')->nullable();
            $table->string('postedBy',20)->nullable();
            $table->boolean('jvFlag')->nullable();
            $table->boolean('exportFlag')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
