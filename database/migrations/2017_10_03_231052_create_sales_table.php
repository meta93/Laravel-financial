<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compCode',false);
            $table->foreign('compCode')->references('compCode')->on('companies')->onDelete('restrict');
            $table->integer('invoiceno',false)->unsigned()->comments('salse Invoice #');
            $table->char('type',1)->comments('L = Local Salse, E = Export B=b2b l/c'); //1 for consumption 2 for purchase
            $table->date('invoicedate');
            $table->integer('relationship_id')->unsigned()->nullable()->index('FK_purchase_relationships')->comments('Suppliers');
            $table->foreign('relationship_id')->references('id')->on('relationships')->onDelete('restrict');
            $table->decimal('invoice_amt',15,2)->default(0.00);
            $table->decimal('paid_amt',15,2)->default(0.00);
            $table->decimal('due_amt',15,2)->default(0.00);
            $table->integer('approver')->unsigned()->nullable();
            $table->foreign('approver')->references('id')->on('users')->onDelete('restrict');
            $table->string('description')->nullable();
            $table->tinyInteger('status',false)->unsigned()->default(1)->comments('1 = created, 2= approved, 3= received, 4= purchased,  5=delevered, 6= rejected, 7=closed');
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
        Schema::dropIfExists('sales');
    }
}
