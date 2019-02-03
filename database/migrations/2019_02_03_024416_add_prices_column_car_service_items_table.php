<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPricesColumnCarServiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('car_service_items', function (Blueprint $table) {
            $table->unsignedDecimal('low_price')->default(0)->after('low');
            $table->unsignedDecimal('mid_price')->default(0)->after('mid');
            $table->unsignedDecimal('high_price')->default(0)->after('high');
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
        Schema::table('car_service_items', function (Blueprint $table) {
            $table->dropColumn('low_price');
            $table->dropColumn('mid_price');
            $table->dropColumn('high_price');
        });
    }
}
