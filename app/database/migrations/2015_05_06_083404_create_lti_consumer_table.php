<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLtiConsumerTable extends Migration {

    private $tableName = 'lti_consumer';
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create($this->tableName, function(Blueprint $table)
        {
            $table->engine = 'InnoDB';

            $table->string('consumer_key', 255)->primary();
            $table->string('name', 45);
            $table->string('secret', 32);

            $table->string('lti_version', 12)->nullable()->default(NULL);
            $table->string('consumer_name', 255)->nullable()->default(NULL);
            $table->string('consumer_version', 255)->nullable()->default(NULL);
            $table->string('consumer_guid', 255)->nullable()->default(NULL);
            $table->string('css_path', 255)->nullable()->default(NULL);

            $table->tinyInteger('protected', 1);
            $table->tinyInteger('enabled', 1);

            $table->dateTime('enable_from')->nullable()->default(NULL);
            $table->dateTime('enable_until')->nullable()->default(NULL);

            $table->date('last_access')->nullable()->default(NULL);

            $table->dateTime('created');
            $table->dateTime('updated');
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
