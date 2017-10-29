<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCoordinatorToCoordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coordinations', function (Blueprint $table) {
            $table->integer('coordinator_id')
                ->unsigned()
                ->nullable();
            
            $table->foreign('coordinator_id')->references('id')
                ->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
        Schema::table('users',function (Blueprint $table) {
            $table->dropForeign('users_coordinator_id_foreign');
            $table->dropColumn('coordinator_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coordinations', function (Blueprint $table) {
            $table->dropForeign('coordinations_coordinator_id_foreign');
            $table->dropColumn('coordinator_id');
        });
        Schema::table('users', function (Blueprint $table) {
             $table->integer('coordinator_id')
                ->unsigned()
                ->nullable();
            $table->foreign('coordinator_id')
                ->references('id')->on('coordinations')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
    }
}
