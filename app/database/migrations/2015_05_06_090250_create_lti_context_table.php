<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLtiContextTable extends Migration {

    private $tableName = 'lti_context';
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
            $table->string('context_id', 255)->primary();

            $table->string('lti_context_id', 255)->nullable()->default(NULL);
            $table->string('lti_resource_id', 255)->nullable()->default(NULL);

            $table->string('title', 255);

            $table->text('settings')->nullable();

            $table->string('primary_consumer_key', 255)->nullable()->default(NULL);
            $table->string('primary_context_id', 255)->nullable()->default(NULL);
            $table->index(array('primary_consumer_key', 'primary_context_id'));

            $table->tinyInteger('share_approved')->nullable()->default(NULL);

            $table->dateTime('created');
            $table->dateTime('updated');

            $table->foreign('consumer_key')->references('consumer_key')->on('lti_consumer');
            $table->foreign(array('primary_consumer_key', 'primary_context_id'))->references(array('consumer_key', 'context_id'))->on('lti_context');
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
