<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patrimony_number');
            $table->string('item');
            
            //Colaborador com o item
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')
                ->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            //Coordenação do item
            $table->integer('coordination_id')->unsigned();
            $table->foreign('coordination_id')->references('id')
                ->on('coordinations')
                ->onDelete('no action')
                ->onUpdate('cascade');

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
        Schema::table('itens', function (Blueprint $table) {
            $table->dropForeign('itens_user_id_foreign');
        });
        Schema::dropIfExists('itens');
    }
}
