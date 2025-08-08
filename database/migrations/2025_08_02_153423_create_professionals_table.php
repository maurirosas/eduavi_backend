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
        Schema::create('professionals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->unique();
            $table->string('specialty')->nullable();
            $table->text('description')->nullable();
            $table->text('experience')->nullable();
            $table->string('location')->nullable();
            $table->text('bio')->nullable();
            $table->float('average_rating')->default(0);
            $table->integer('patient_count')->default(0);
            $table->string('bank_account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('national_id')->nullable();
            $table->string('profession')->nullable();
            $table->enum('status', ['active', 'inactive', 'verified'])->default('inactive');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professionals');
    }
};
