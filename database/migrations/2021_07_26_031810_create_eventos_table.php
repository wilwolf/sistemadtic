<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_titulo');
            $table->unsignedBigInteger('id_user');
            $table->string('modalidad');
            $table->string('version');
            $table->string('cargah');
            $table->date('fechainicio');
            $table->date('fechafin');
            $table->string('nombrex');
            $table->string('nombrey');
            $table->string('qrx');
            $table->string('qry');
            $table->longText('contenido');
            $table->string('estado');
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
        Schema::dropIfExists('eventos');
    }
}
