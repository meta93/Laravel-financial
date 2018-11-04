<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compCode',false);
            $table->foreign('compCode')->references('compCode')->on('companies')->onDelete('cascade');
            $table->string('name',160);
            $table->string('productCode',8);
            $table->unique(array('compCode', 'productCode'));
            $table->integer('relationship_id')->unsigned()->nullable()->index('FK_products_relationships');
            $table->foreign('relationship_id')->references('id')->on('relationships')->onDelete('restrict');
            $table->integer('brand_id')->unsigned()->nullable()->index('FK_products_brands');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('restrict');
            $table->integer('category_id')->unsigned()->index('FK_products_categories');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict');
            $table->integer('subcategory_id')->unsigned()->nullable()->index('FK_products_sub_categories');
            $table->foreign('subcategory_id')->references('id')->on('sub_categories')->onDelete('restrict');
            $table->string('unit_name',10)->index('FK_products_units');
            $table->foreign('unit_name')->references('name')->on('units')->onUpdate('CASCADE')->onDelete('restrict');
            $table->boolean('varient')->default(0);
            $table->string('size',60)->nullable();
            $table->string('color',60)->nullable();
            $table->string('sku', 50);
            $table->unique(array('compCode','sku'));
            $table->integer('model_id')->unsigned()->nullable()->index('FK_products_models');
            $table->foreign('model_id')->references('id')->on('models')->onDelete('restrict');
            $table->integer('taxgrp_id')->unsigned()->nullable()->index('FK_products_taxgroup');
            $table->foreign('taxgrp_id')->references('id')->on('tax_groups')->onDelete('restrict');
            $table->integer('tax_id')->unsigned()->nullable()->index('FK_products_tax');
            $table->foreign('tax_id')->references('id')->on('taxes')->onDelete('restrict');
            $table->integer('godown_id')->unsigned()->nullable()->index('FK_products_godowns');
            $table->foreign('godown_id')->references('id')->on('godowns')->onDelete('restrict');
            $table->integer('rack_id')->unsigned()->nullable()->index('FK_products_racks');
            $table->foreign('rack_id')->references('id')->on('racks')->onDelete('restrict');
            $table->decimal('initialPrice',15,2)->default(0.00);
            $table->decimal('buyPrice',15,2)->default(0.00);
            $table->decimal('wholesalePrice',15,2)->default(0.00);
            $table->decimal('retailPrice',15,2)->default(0.00);
            $table->decimal('unitPrice',15,2)->default(0.00);
            $table->decimal('reorderpoint',15,2)->default(0);
            $table->decimal('openingQty',15,2)->default(0);
            $table->decimal('openingValue',15,2)->default(0);
            $table->decimal('onhand',15,2)->default(0);
            $table->decimal('committed',15,2)->default(0);
            $table->decimal('incomming',15,2)->default(0);
            $table->decimal('maxonlinestock',15,2)->default(0);
            $table->decimal('minonlineorder',15,2)->default(0);
            $table->decimal('purchaseQty',15,2)->default(0);
            $table->decimal('sellQty',15,2)->default(0);
            $table->decimal('salvageQty',15,2)->default(0);
            $table->boolean('saleable')->default(0);
            $table->decimal('receivedQty',15,2)->default(0);
            $table->decimal('returnQty',15,2)->default(0);
            $table->integer('shipping')->unsigned()->nullable()->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->text('description_short')->nullable();
            $table->text('description_long')->nullable();
            $table->text('stuff_included')->nullable();
            $table->float('warranty_period', 10, 0)->unsigned()->nullable();
            $table->string('image',195)->nullable();
            $table->string('image_large',195)->nullable();
            $table->boolean('sellable')->default(true);
            $table->boolean('purchasable')->default(true);
            $table->boolean('b2bpublish')->default(false);
            $table->boolean('free')->unsigned()->default(0);
            $table->boolean('taxable')->unsigned()->default(1);
            $table->boolean('status')->unsigned()->default(1);
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
        Schema::dropIfExists('products');
    }
}
