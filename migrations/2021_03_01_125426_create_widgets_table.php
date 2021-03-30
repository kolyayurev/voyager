<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWidgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widgets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('handler');
            $table->text('description')->nullable();
            $table->string('table_name')->nullable();
            $table->bigInteger('foreign_key')->unsigned()->nullable();
            $table->text('details')->nullable();
            $table->json('value')->nullable();
            $table->unique(['table_name', 'foreign_key']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widgets');
    }
}
