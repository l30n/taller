<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPricesColumnSaleServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('sale_services', function (Blueprint $table) {
            $table->unsignedInteger('item_id')->after('service_id');
            $table->unsignedDecimal('price')->default(0)->after('year');

            $table->foreign('sale_id')->references('id')->on('sales');
            $table->foreign('car_id')->references('id')->on('cars');
            $table->foreign('service_id')->references('id')->on('services');
            $table->foreign('item_id')->references('id')->on('items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('sale_services', function (Blueprint $table) {
            $table->dropColumn('item_id');
            $table->dropColumn('price');

            $table->dropForeign('sale_id');
            $table->dropForeign('car_id');
            $table->dropForeign('service_id');
            $table->dropForeign('item_id');
        });
    }
}
