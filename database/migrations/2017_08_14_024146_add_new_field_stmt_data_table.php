<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldStmtDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stmt_data', function (Blueprint $table) {
            $table->decimal('fp00',15,2)->nullable()->default(0);
            $table->decimal('fp01',15,2)->nullable()->default(0);
            $table->decimal('fp02',15,2)->nullable()->default(0);
            $table->decimal('fp03',15,2)->nullable()->default(0);
            $table->decimal('fp04',15,2)->nullable()->default(0);
            $table->decimal('fp05',15,2)->nullable()->default(0);
            $table->decimal('fp06',15,2)->nullable()->default(0);
            $table->decimal('fp07',15,2)->nullable()->default(0);
            $table->decimal('fp08',15,2)->nullable()->default(0);
            $table->decimal('fp09',15,2)->nullable()->default(0);
            $table->decimal('fp10',15,2)->nullable()->default(0);
            $table->decimal('fp11',15,2)->nullable()->default(0);
            $table->decimal('fp12',15,2)->nullable()->default(0);
            $table->decimal('pfp01',15,2)->nullable()->default(0);
            $table->decimal('pfp02',15,2)->nullable()->default(0);
            $table->decimal('pfp03',15,2)->nullable()->default(0);
            $table->decimal('pfp04',15,2)->nullable()->default(0);
            $table->decimal('pfp05',15,2)->nullable()->default(0);
            $table->decimal('pfp06',15,2)->nullable()->default(0);
            $table->decimal('pfp07',15,2)->nullable()->default(0);
            $table->decimal('pfp08',15,2)->nullable()->default(0);
            $table->decimal('pfp09',15,2)->nullable()->default(0);
            $table->decimal('pfp10',15,2)->nullable()->default(0);
            $table->decimal('pfp11',15,2)->nullable()->default(0);
            $table->decimal('pfp12',15,2)->nullable()->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stmt_data', function (Blueprint $table) {
            $table->dropColumn('fp01');
            $table->dropColumn('fp02');
            $table->dropColumn('fp03');
            $table->dropColumn('fp04');
            $table->dropColumn('fp05');
            $table->dropColumn('fp06');
            $table->dropColumn('fp07');
            $table->dropColumn('fp08');
            $table->dropColumn('fp09');
            $table->dropColumn('fp10');
            $table->dropColumn('fp11');
            $table->dropColumn('fp12');
            $table->dropColumn('pfp01');
            $table->dropColumn('pfp02');
            $table->dropColumn('pfp03');
            $table->dropColumn('pfp04');
            $table->dropColumn('pfp05');
            $table->dropColumn('pfp06');
            $table->dropColumn('pfp07');
            $table->dropColumn('pfp08');
            $table->dropColumn('pfp09');
            $table->dropColumn('pfp10');
            $table->dropColumn('pfp11');
            $table->dropColumn('pfp12');


        });
    }
}
