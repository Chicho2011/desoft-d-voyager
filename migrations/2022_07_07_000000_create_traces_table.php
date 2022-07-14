<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('traces', function (Blueprint $table) {
            $table->id();
            $table->string('user');
            $table->string('action');
            $table->string('table');
            $table->text('fields')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('traces');
    }
};