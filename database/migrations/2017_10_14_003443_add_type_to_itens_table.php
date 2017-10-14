<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToItensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('itens', function (Blueprint $table) {
            //Coordenação do item
            $table->integer('type_id')->unsigned();
            $table->foreign('type_id')->references('id')
                ->on('item_types')
                ->onDelete('no action')
                ->onUpdate('cascade');
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
            $table->dropForeign('itens_type_id_foreign');
            $table->dropColumn('type_id');
        });
    }
}
