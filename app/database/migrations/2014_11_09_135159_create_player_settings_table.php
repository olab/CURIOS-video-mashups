<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayerSettingsTable extends Migration {

    private $tableName = 'player_settings';
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
            $table->string('name');
            $table->integer('width');
            $table->integer('height');
            $table->integer('start_time');
            $table->integer('end_time');
            $table->integer('sound_level');
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
