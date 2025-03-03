<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueConstraintToCakeEventDevelopers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cake_event_developers', function (Blueprint $table) {
            $table->unique(['event_id', 'developer_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cake_event_developers', function (Blueprint $table) {
            $table->dropUnique(['event_id', 'developer_id']);
        });
    }
}
