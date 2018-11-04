<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compCode',false);
            $table->string('projCode',6);
            $table->string('projType',20)->nullable();
            $table->string('projName',60);
            $table->string('projDesc',200);
            $table->string('projRef',60)->nullable();
            $table->date('startDate');
            $table->date('endDate');
            $table->boolean('status')->default(True);
            $table->decimal('projBudget',15,2)->nullable()->default(0);
            $table->decimal('expense',15,2)->nullable()->default(0);
            $table->decimal('income',15,2)->nullable()->default(0);
            $table->string('userCreated',20);
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->foreign('compCode')
                ->references('compCode')->on('companies')
                ->onDelete('cascade');
            $table->unique(array('compCode', 'projCode'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
