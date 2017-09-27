<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoordinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coordinations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('coordination_id')->unsigned();
            $table->foreign('coordination_id')
                ->references('id')->on('coordinations')
                ->onDelete('restrict')
                ->onUpdate('restrict');
            
            $table->integer('coordinator_id')
                ->unsigned()
                ->nullable();
            $table->foreign('coordinator_id')
                ->references('id')->on('coordinations')
                ->onDelete('restrict')
                ->onUpdate('restrict');
            
            $table->unique('coordinator_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_coordinator_id_foreign');
            $table->dropForeign('users_coordination_id_foreign');
            $table->dropColumn(['coordinator_id','coordination_id']);
        });
        Schema::dropIfExists('coordinations');
    }
}
