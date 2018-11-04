<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compCode',false);
            $table->string('ldgrCode',3)->nullable();
            $table->string('accNo',8);
            $table->string('accName',50);
            $table->string('accType',1);
            $table->integer('typeCode',false);
            $table->string('accrNo',8)->nullable();
            $table->string('userCreated',20)->nullable();
            $table->boolean('isGroup')->nullable();
            $table->decimal('opnDr',15,2)->nullable()->default(0);
            $table->decimal('opnCr',15,2)->nullable()->default(0);
            $table->decimal('startDr',15,2)->nullable()->default(0);
            $table->decimal('startCr',15,2)->nullable()->default(0);
            $table->decimal('currBal',15,2)->default(0);
            $table->decimal('cyrDr',15,2)->nullable()->default(0);
            $table->decimal('cyrCr',15,2)->nullable()->default(0);
            $table->decimal('dr00',15,2)->nullable()->default(0);// current year debit with unposted
            $table->decimal('cr00',15,2)->nullable()->default(0);//current year credit with unposted
            $table->decimal('pyrdr',15,2)->nullable()->default(0);
            $table->decimal('pyrcr',15,2)->nullable()->default(0);
            $table->decimal('dr01',15,2)->nullable()->default(0);
            $table->decimal('cr01',15,2)->nullable()->default(0);
            $table->decimal('dr02',15,2)->nullable()->default(0);
            $table->decimal('cr02',15,2)->nullable()->default(0);
            $table->decimal('dr03',15,2)->nullable()->default(0);
            $table->decimal('cr03',15,2)->nullable()->default(0);
            $table->decimal('dr04',15,2)->nullable()->default(0);
            $table->decimal('cr04',15,2)->nullable()->default(0);
            $table->decimal('dr05',15,2)->nullable()->default(0);
            $table->decimal('cr05',15,2)->nullable()->default(0);
            $table->decimal('dr06',15,2)->nullable()->default(0);
            $table->decimal('cr06',15,2)->nullable()->default(0);
            $table->decimal('dr07',15,2)->nullable()->default(0);
            $table->decimal('cr07',15,2)->nullable()->default(0);
            $table->decimal('dr08',15,2)->nullable()->default(0);
            $table->decimal('cr08',15,2)->nullable()->default(0);
            $table->decimal('dr09',15,2)->nullable()->default(0);
            $table->decimal('cr09',15,2)->nullable()->default(0);
            $table->decimal('dr10',15,2)->nullable()->default(0);
            $table->decimal('cr10',15,2)->nullable()->default(0);
            $table->decimal('dr11',15,2)->nullable()->default(0);
            $table->decimal('cr11',15,2)->nullable()->default(0);
            $table->decimal('dr12',15,2)->nullable()->default(0);
            $table->decimal('cr12',15,2)->nullable()->default(0);
            $table->decimal('cyrBgtp',15,2)->nullable()->default(0);
            $table->decimal('cyrBbgtr',15,2)->nullable()->default(0);
            $table->decimal('cyrBbgta',15,2)->nullable()->default(0);
            $table->decimal('lyrBbgt',15,2)->nullable()->default(0);
            $table->decimal('pyrBbgt',15,2)->nullable()->default(0);
            $table->decimal('tmpBdr',15,2)->nullable()->default(0);
            $table->decimal('tmpBcr',15,2)->nullable()->default(0);
            $table->decimal('bdgt01',15,2)->nullable()->default(0);
            $table->decimal('bdgt02',15,2)->nullable()->default(0);
            $table->decimal('bdgt03',15,2)->nullable()->default(0);
            $table->decimal('bdgt04',15,2)->nullable()->default(0);
            $table->decimal('bdgt05',15,2)->nullable()->default(0);
            $table->decimal('bdgt06',15,2)->nullable()->default(0);
            $table->decimal('bdgt07',15,2)->nullable()->default(0);
            $table->decimal('bdgt08',15,2)->nullable()->default(0);
            $table->decimal('bdgt09',15,2)->nullable()->default(0);
            $table->decimal('bdgt10',15,2)->nullable()->default(0);
            $table->decimal('bdgt11',15,2)->nullable()->default(0);
            $table->decimal('bdgt12',15,2)->nullable()->default(0);
            $table->decimal('fcBdgt',15,2)->nullable()->default(0);
            $table->decimal('fcBalDr',15,2)->nullable()->default(0);
            $table->decimal('fcBalCr',15,2)->nullable()->default(0);
            $table->boolean('opnPost')->default(0);
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->foreign('compCode')
                ->references('compCode')->on('companies')
                ->onDelete('cascade');
            $table->unique(array('compCode', 'accNo'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
