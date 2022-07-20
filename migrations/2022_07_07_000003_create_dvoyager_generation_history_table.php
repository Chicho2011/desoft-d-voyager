<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('dvoyager_generation_history', function (Blueprint $table) {
            $table->id();
            $table->string('bread');
            $table->string('model');
            $table->string('migration');
            $table->string('table');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dvoyager_generation_history');
    }
};