<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    //Banco de dados do card
    public function up(): void
    {
        Schema::create('cards', function(Blueprint $table){
            $table -> id();
            $table -> string('title', 64);
            $table -> string('description', 256)->nullable();
            $table -> string('filename');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
