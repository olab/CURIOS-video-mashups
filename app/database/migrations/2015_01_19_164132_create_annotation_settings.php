<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnnotationSettings extends Migration {

    private $tableName = 'annotation_settings';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create($this->tableName, function(Blueprint $table)
        {
            $table->increments('id');
            $table->text('form');
            $table->text('backGround');
            $table->integer('x');
            $table->integer('y');
            $table->integer('height');
            $table->integer('width');
            $table->integer('start_time');
            $table->integer('end_time');
            $table->text('text');
            $table->double('transparency');
            $table->integer('fontSize');
            $table->string('color');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop($this->tableName, function(Blueprint $table)
        {
            //
        });
	}

}
