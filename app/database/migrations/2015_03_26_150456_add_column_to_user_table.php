<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToUserTable extends Migration {

    private $tableName = 'users';
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table($this->tableName, function(Blueprint $table)
		{
            $table->enum('status', array('superuser', 'author'))->after('password');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table($this->tableName, function(Blueprint $table)
		{
            $table->dropColumn('status');
		});
	}

}
