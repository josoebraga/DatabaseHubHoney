<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('importacao_dinamica', function (Blueprint $table) {
            $table->id();
            $table->string('coluna_1')->nullable();
            $table->string('coluna_2')->nullable();
            $table->string('coluna_3')->nullable();
            $table->string('coluna_4')->nullable();
            $table->string('coluna_5')->nullable();
            $table->string('coluna_6')->nullable();
            $table->string('coluna_7')->nullable();
            $table->string('coluna_8')->nullable();
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
        Schema::dropIfExists('teste');
    }
};
