<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compCode',false);
            $table->foreign('compCode')->references('compCode')->on('companies')->onDelete('restrict');
            $table->integer('refno',false)->unsigned();
            $table->integer('contra',false)->unsigned()->comments('Invoice No, Purchase Order No, Requisition No, Challan N');
            $table->char('reftype',1)->comments('P = Purchase, R = Requisition, S = Sales, I = Import, D = Delivery'); //1 for consumption 2 for purchase
            $table->string('toWhome')->nullable()->comments('For which department this was created');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('restrict');
            $table->string('name',160);
            $table->decimal('quantity',15,2)->default(0.00);
            $table->decimal('unit_price',15,2)->default(0.00);
            $table->integer('tax_id')->unsigned()->nullable()->index('FK_products_tax');
            $table->foreign('tax_id')->references('id')->on('taxes')->onDelete('restrict');
            $table->decimal('tax_total',15,2)->default(0.00);
            $table->decimal('total_price',15,2)->default(0.00);
            $table->decimal('approved',15,2)->default(0.00);
            $table->decimal('purchased',15,2)->default(0.00);
            $table->decimal('sold',15,2)->default(0.00);
            $table->decimal('received',15,2)->default(0.00);
            $table->decimal('returned',15,2)->default(0.00);
            $table->decimal('delevered',15,2)->default(0.00);
            $table->string('remarks',190)->nullable();
            $table->tinyInteger('status',false)->unsigned()->default(1)->comments('1 = created, 2= approved, 3= purchased, 4= received, 5=delevered, 6= rejected, 7=closed');
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
        Schema::dropIfExists('trans_products');
    }
}
