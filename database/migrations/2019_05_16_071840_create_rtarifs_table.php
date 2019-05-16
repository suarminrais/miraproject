<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRtarifsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rtarifs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->string('kelas');
            $table->string('kategori');
            $table->integer('nokamar');
            $table->integer('nott');
            $table->integer('tarif');
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
        Schema::dropIfExists('rtarifs');
    }
}
