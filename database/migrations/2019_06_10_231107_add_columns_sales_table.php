<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('sales', function (Blueprint $table) {
            $table->text('last_service')->after('color')->default('');
            $table->text('km')->after('last_service')->default('');
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
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('last_service');
            $table->dropColumn('km');
        });
    }
}
