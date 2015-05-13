<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLtiUserTable extends Migration {

    private $tableName = 'lti_user';
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

            $table->string('consumer_key', 255);
            $table->string('context_id', 255);
            $table->string('user_id', 255);

            $table->string('lti_result_sourcedid', 255);

            $table->dateTime('created');
            $table->dateTime('updated');

            $table->primary(array('consumer_key', 'context_id', 'user_id'));

            $table->foreign(array('consumer_key', 'context_id'))->references(array('consumer_key', 'context_id'))->on('lti_context');

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
