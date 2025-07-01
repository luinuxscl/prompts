<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prompt_relations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_prompt_id');
            $table->unsignedBigInteger('child_prompt_id');
            $table->timestamps();

            $table->foreign('parent_prompt_id')->references('id')->on('prompts')->onDelete('cascade');
            $table->foreign('child_prompt_id')->references('id')->on('prompts')->onDelete('cascade');
            $table->unique(['parent_prompt_id', 'child_prompt_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prompt_relations');
    }
};
