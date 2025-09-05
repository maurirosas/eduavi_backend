<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('speech_language_forms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('kardex_id');
            $table->uuid('professional_id');

            $table->date('interview_date');
            $table->time('interview_time');
            $table->string('diagnosis_type')->nullable();

            $table->json('patient_data')->nullable();
            $table->json('parent_data')->nullable();
            $table->json('medical_background')->nullable();
            $table->json('language_development')->nullable();
            $table->json('comprehension')->nullable();
            $table->json('expression')->nullable();
            $table->json('interaction_play')->nullable();
            $table->json('behavior')->nullable();
            $table->json('unusual_behaviors')->nullable();

            $table->timestamps();

            $table->foreign('kardex_id')->references('id')->on('kardexes')->onDelete('cascade');
            $table->foreign('professional_id')->references('id')->on('professionals')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('speech_language_forms');
    }
};
