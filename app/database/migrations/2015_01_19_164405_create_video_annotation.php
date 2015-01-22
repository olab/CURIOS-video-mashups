<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoAnnotation extends Migration {

    private $tableName = "video_annotation";

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
            $table->integer('annotation_id')->unsigned();

            $table->foreign('video_id')->references('id')->on('video_settings')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('annotation_id')->references('id')->on('annotation_settings')->onDelete('cascade')->onUpdate('cascade');
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
