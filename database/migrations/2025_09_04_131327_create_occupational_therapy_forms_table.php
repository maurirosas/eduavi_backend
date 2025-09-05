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
        Schema::create('occupational_therapy_forms', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('kardex_id');
            $table->uuid('professional_id');

            $table->date('interview_date')->nullable();
            $table->time('interview_time')->nullable();
            $table->string('intervention_type')->nullable();

            // Secciones como JSON
            $table->json('patient_data')->nullable();
            $table->json('parent_data')->nullable();
            $table->json('medical_history')->nullable();
            $table->json('development_milestones')->nullable();
            $table->json('daily_living_activities')->nullable();
            $table->json('sensory_neuromotor_system')->nullable();
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
        Schema::dropIfExists('occupational_therapy_forms');
    }
};
