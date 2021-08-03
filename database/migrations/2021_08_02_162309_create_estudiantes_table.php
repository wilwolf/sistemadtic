<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstudiantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('carnet');
            $table->string('complemento', 20);
            $table->unsignedBigInteger('extension');
            $table->string('nombre', 50);
            $table->string('apellidos', 50);
            $table->bigInteger('telefono');
            $table->string('email', 50);
            $table->string('imagen', 255);
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
        Schema::dropIfExists('estudiantes');
    }
}
