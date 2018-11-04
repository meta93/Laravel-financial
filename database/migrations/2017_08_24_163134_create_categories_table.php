<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compCode',false);
            $table->string('name', 100)->unique('name');
            $table->unique(array('compCode','name'));
            $table->string('alias', 100)->nullable();
            $table->string('accNo',8)->nullable();
            $table->decimal('inventoryAmt',15,2)->default(0)->comment('Current balance * avg unit price'); //Amount calculated by current balance*avg unit proce
            $table->decimal('accBalance',15,2)->default(0)->comment('General Ledger Balance');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->foreign('compCode')
                ->references('compCode')->on('companies')
                ->onDelete('cascade');
        });

        DB::unprepared('CREATE TRIGGER copy_alias BEFORE INSERT ON categories FOR EACH ROW
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
        Schema::dropIfExists('categories');
    }
}
