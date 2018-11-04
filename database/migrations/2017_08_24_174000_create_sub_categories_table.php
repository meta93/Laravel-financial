<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compCode',false);
            $table->string('name', 50);
            $table->unique(array('compCode','name'));
            $table->integer('category_id')->unsigned()->index('FK_sub_categories_categories');
            $table->string('alias', 50)->nullable();
            $table->integer('user_id')->unsigned();
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->boolean('deleted')->default(false);
            $table->softDeletes();
            $table->foreign('compCode')
                ->references('compCode')->on('companies')
                ->onDelete('cascade');
        });

        Schema::table('sub_categories', function($table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('sub_categories', function(Blueprint $table)
        {
            $table->foreign('category_id', 'FK_sub_categories_categories')->references('id')->on('categories')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });

        DB::unprepared('CREATE TRIGGER copy_alias_sub BEFORE INSERT ON sub_categories FOR EACH ROW
                IF NEW.alias IS NULL OR LENGTH(NEW.alias) < 1 THEN
                SET NEW.alias := NEW.name;
                END IF;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_categories');
    }
}
