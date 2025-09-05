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
        Schema::create('psychopedagogic_forms', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('kardex_id');
            $table->uuid('professional_id');

            $table->date('interview_date')->nullable();
            $table->time('interview_time')->nullable();
            $table->string('intervention_type')->nullable();

            $table->json('patient_data')->nullable();
            $table->json('parent_data')->nullable();
            $table->json('development_data')->nullable();
            $table->json('language_data')->nullable();
            $table->json('social_data')->nullable();
            $table->json('cognitive_data')->nullable();
            $table->json('interaction_play_data')->nullable();
            $table->json('behavior_data')->nullable();
            $table->json('autonomy_data')->nullable();
            $table->json('atypical_behaviors')->nullable();

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
        Schema::dropIfExists('psychopedagogic_forms');
    }
};
