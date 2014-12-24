<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAudioSettings extends Migration {

    private $tableName = 'audio_settings';
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
            $table->text('src');
            $table->integer('start_time');
            $table->integer('end_time');
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
