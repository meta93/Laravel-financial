<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compCode',false);
            $table->integer('tax_id',false)->unsigned();
            $table->unique(array('compCode', 'tax_id'));
            $table->string('taxName',120);
            $table->char('applicableOn',1)->default('S')->comment('P=Purchase ; S= Sales ;');
            $table->decimal('rate',15,2)->default(0);
            $table->string('calculatingMode',60)->default('P')->comment('P=Percentage ; F= Fixed Amount ;'); //P=Percentage  F= Fixed Amount
            $table->string('description',120)->nullable();
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('taxes');
    }
}
