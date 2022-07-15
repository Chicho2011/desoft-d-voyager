<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('searches', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('searchable_id');
            $table->string('searchable_type');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('searches');
    }
};