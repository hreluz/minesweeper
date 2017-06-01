<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlayingGridToMinesweepersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('minesweepers', function (Blueprint $table) {
            $table->mediumText('playing_grid')->nullable()->after('grid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('minesweepers', function (Blueprint $table) {
            $table->dropColumn('playing_grid');
        });
    }
}
