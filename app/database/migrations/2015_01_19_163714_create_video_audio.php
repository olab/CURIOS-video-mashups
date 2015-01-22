<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoAudio extends Migration {

    private $tableName = 'video_audio';

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
            $table->integer('video_id')->unsigned();
            $table->integer('audio_id')->unsigned();

            $table->foreign('video_id')->references('id')->on('video_settings')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('audio_id')->references('id')->on('audio_settings')->onDelete('cascade')->onUpdate('cascade');
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
