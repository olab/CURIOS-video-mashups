<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLtiNonceTable extends Migration {

    private $tableName = 'lti_nonce';
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
            $table->string('value', 32);

            $table->dateTime('expires');

            $table->primary(array('consumer_key', 'value'));

            $table->foreign('consumer_key')->references('consumer_key')->on('lti_consumer');
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
