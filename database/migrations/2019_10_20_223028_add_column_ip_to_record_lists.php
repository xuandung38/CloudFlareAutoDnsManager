<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIpToRecordLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('record_lists', function (Blueprint $table) {
            $table->ipAddress('old_ip')->nullable();
            $table->ipAddress('new_ip')->nullable();
            $table->string('id_record')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('record_lists', function (Blueprint $table) {
            $table->removeColumn('old_ip');
            $table->removeColumn('new_ip');
            $table->removeColumn('id_record');
        });
    }
}
