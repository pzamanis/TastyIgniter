<?php namespace Admin\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Schema;

/**
 * Add validity columns on menus_specials table
 */
class addColumnsOnMenuSpecialsTable extends Migration
{
    public function up()
    {
        Schema::table('menus_specials', function (Blueprint $table) {
            $table->string('validity');
            $table->dateTime('start_date')->change();
            $table->dateTime('end_date')->change();
            $table->text('recurring_every')->nullable();
            $table->time('recurring_from')->nullable();
            $table->time('recurring_to')->nullable();
        });
    }

    public function down()
    {
        //
    }
}