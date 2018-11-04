<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_movements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compCode',false);
            $table->foreign('compCode')->references('compCode')->on('companies')->onDelete('restrict');
            $table->integer('refno',false)->unsigned();
            $table->bigInteger('barcode')->unsigned()->default(0);
            $table->integer('contra',false)->unsigned()->comments('Invoice No, Purchase Order No, Requisition No, Challan N');
            $table->char('reftype',1)->comments('R = Receive, D = Delivery'); //1 for consumption 2 for purchase
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('restrict');
            $table->decimal('quantity',15,2)->default(0.00);
            $table->decimal('received',15,2)->default(0.00);
            $table->decimal('returned',15,2)->default(0.00);
            $table->decimal('delevered',15,2)->default(0.00);
            $table->string('remarks',190)->nullable();
            $table->tinyInteger('status',false)->unsigned()->default(1)->comments('1 = created, 2= approved, 3= purchased, 4= received, 5=delevered, 6= rejected, 7=closed');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->boolean('deleted')->default(false);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_movements');
    }
}
