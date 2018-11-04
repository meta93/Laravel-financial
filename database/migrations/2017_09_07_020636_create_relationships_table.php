<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relationships', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compCode',false);
            $table->foreign('compCode')->references('compCode')->on('companies')->onDelete('cascade');
            $table->string('type',160);
            $table->string('company_code',160)->nullable();
            $table->unique(array('compCode', 'company_code'));
            $table->string('name',160);
            $table->unique(array('compCode', 'name'));
            $table->string('tax_number',60)->nullable();
            $table->string('email',120)->nullable();
            $table->string('glcode',8)->nullable();
            $table->string('street',200)->nullable();
            $table->string('address',200)->nullable();
            $table->string('city',200)->nullable();
            $table->string('state',200)->nullable();
            $table->string('country',200)->nullable();
            $table->string('zipcode',200)->nullable();
            $table->string('phone_number',200)->nullable();
            $table->string('fax_number',200)->nullable();
            $table->string('website',200)->nullable();
            $table->string('asigned',200)->nullable();
            $table->string('default_price',20)->nullable()->comment('Whole Sale / Retail');
            $table->integer('default_discount',false)->unsigned()->default(0);
            $table->string('default_payment_term',60)->nullable();
            $table->string('default_payment_method',60)->nullable();
            $table->decimal('min_order_value',15,2)->default(0);
            $table->boolean('status')->default(true);
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->boolean('deleted')->default(false);
            $table->softDeletes(); // <-- This will add a deleted_at field
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relationships');
    }
}
