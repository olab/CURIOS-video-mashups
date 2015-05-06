<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddCreatedByLtiToUserTable extends Migration {

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
            $table->tinyInteger('created_by_lti', 1);
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
            $table->dropColumn('created_by_lti');
        });
    }
}
