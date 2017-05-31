<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinesweepersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('minesweepers', function (Blueprint $table) {
            $table->increments('id');
            
            $table->mediumText('grid');
            $table->integer('x');
            $table->integer('y');
            $table->string('token');

            $table->boolean('is_finished')->default(false);
            $table->boolean('succes_game')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('minesweepers');
    }
}
