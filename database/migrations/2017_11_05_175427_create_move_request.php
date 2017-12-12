<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoveRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('move_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_from_id')
                ->unsigned();
            $table->foreign('user_from_id')->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->integer('user_to_id')
                ->nullable()
                ->unsigned();
            $table->foreign('user_to_id')->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->integer('coordination_id')
                ->nullable()
                ->unsigned();
            $table->foreign('coordination_id')->references('id')
                ->on('coordinations')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->string('description')
                ->nullable();

            $table->integer('item_id')
                ->unsigned();
            $table->foreign('item_id')->references('id')
                ->on('itens')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->boolean('resolved')
                ->comment('Indica se a solicitação de mudanças já foi resolvida')
                ->default(false);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('move_requests', function (Blueprint $table) {
            $table->dropForeign('move_requests_user_from_id_foreign');
            $table->dropForeign('move_requests_user_to_id_foreign');
            $table->dropForeign('move_requests_coordination_id_foreign');
            $table->dropForeign('move_requests_item_id_foreign');
        });
        Schema::dropIfExists('move_requests');
    }
}
