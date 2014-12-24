<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayerPopup extends Migration {

    private $tableName = 'player_popup';
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
            $table->integer('popup_id')->unsigned();

            $table->foreign('player_id')->references('id')->on('player_settings')->onDelete('cascade');
            $table->foreign('popup_id')->references('id')->on('popup_settings')->onDelete('cascade');
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
