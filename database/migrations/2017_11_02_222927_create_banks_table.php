<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compCode',false);
            $table->foreign('compCode')->references('compCode')->on('companies')->onDelete('restrict');
            $table->char('bank_type',1)->comments('E = Exporter Bank; I=Importer Bank; D= Document Sending Bank');
            $table->string('bank_name',60);
            $table->string('branch_name',190);
            $table->string('address',190);
            $table->string('swift_code',20)->nullable();
            $table->string('acc_name',190);
            $table->string('acc_no',30)->unique();
            $table->boolean('status')->defaulr(true);
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
        Schema::dropIfExists('banks');
    }
}
