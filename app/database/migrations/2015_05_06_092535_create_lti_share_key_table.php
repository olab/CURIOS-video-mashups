<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLtiShareKeyTable extends Migration {

    private $tableName = 'lti_share_key';
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

            $table->string('share_key_id', 32)->primary();

            $table->string('primary_consumer_key', 255);
            $table->string('primary_context_id', 255);

            $table->tinyInteger('auto_approve', 1);

            $table->dateTime('expires');

            $table->index(array('primary_consumer_key', 'primary_context_id'));
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
