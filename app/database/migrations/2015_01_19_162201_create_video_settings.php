<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoSettings extends Migration {

    private $tableName = 'video_settings';

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
            $table->string('code');
            $table->integer('start_time');
            $table->integer('end_time');
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
        Schema::drop($this->tableName, function(Blueprint $table)
        {
            //
        });
	}

}
