<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPlayerTable extends Migration {

    private $tableName = 'user_players';
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
            $table->integer('user_id')->unsigned();
            $table->integer('player_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('player_id')->references('id')->on('player_settings')->onDelete('cascade');
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
