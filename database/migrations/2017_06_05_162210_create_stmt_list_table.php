<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStmtListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stmt_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compCode',false);
            $table->string('fileNo',4);
            $table->string('fileDesc',40);
            $table->string('importFile',4)->nullable();
            $table->integer('importLine',false)->nullable()->default(0);
            $table->integer('intoLine',false)->nullable()->default(0);
            $table->string('baseFormula',9)->nullable();
            $table->decimal('importValue',15,2)->nullable()->default(0);
            $table->integer('odby')->nullable();
            $table->integer('user_id')->unsigned();
            $table->date('valueDate')->nullable();
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->foreign('compCode')
                ->references('compCode')->on('companies')
                ->onDelete('RESTRICT');
            $table->unique(array('compCode', 'fileNo'));
        });

        Schema::table('stmt_lists', function($table) {
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
        Schema::dropIfExists('stmt_lists');
    }
}
