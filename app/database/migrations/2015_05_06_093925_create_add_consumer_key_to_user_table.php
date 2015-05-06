<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddConsumerKeyToUserTable extends Migration {

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
            $table->string('consumer_key', 255)->nullable()->default(NULL);
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
            $table->dropColumn('consumer_key');
        });
    }
}
