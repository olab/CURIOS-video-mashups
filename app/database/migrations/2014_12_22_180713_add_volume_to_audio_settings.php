<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVolumeToAudioSettings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('audio_settings', function(Blueprint $table)
		{
            $table->integer('volume');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('audio_settings', function(Blueprint $table)
		{
            $table->dropColumn('volume');
		});
	}

}
