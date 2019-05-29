<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumsSalesTable extends Migration
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
            $table->text('maker')->after('method')->default('');
            $table->text('brand')->after('maker')->default('');
            $table->text('year')->after('brand')->default('');
            $table->text('color')->after('year')->default('');
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
            $table->dropColumn('maker');
            $table->dropColumn('brand');
            $table->dropColumn('year');
            $table->dropColumn('color');
        });
    }
}
