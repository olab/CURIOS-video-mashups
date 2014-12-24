<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayerAudio extends Migration {

    private $tableName = 'player_audio';
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
            $table->integer('player_id')->unsigned();
            $table->integer('audio_id')->unsigned();

            $table->foreign('player_id')->references('id')->on('player_settings')->onDelete('cascade');
            $table->foreign('audio_id')->references('id')->on('audio_settings')->onDelete('cascade');
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
