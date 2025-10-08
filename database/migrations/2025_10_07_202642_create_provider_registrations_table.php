<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provider_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('space_id')->constrained()->cascadeOnDelete();
            $table->foreignId('submitted_by')->constrained('users')->cascadeOnDelete();

            // Company Information
            $table->string('company_name');
            $table->string('company_registration_number')->nullable();
            $table->string('company_country');
            $table->text('company_address');
            $table->string('company_website')->nullable();

            // Contact Information
            $table->string('contact_person_name');
            $table->string('contact_person_title');
            $table->string('contact_person_email');
            $table->string('contact_person_phone')->nullable();

            // AI Information
            $table->text('ai_systems_description');
            $table->json('ai_system_types')->nullable(); // e.g., ["general_purpose", "high_risk"]
            $table->text('intended_use_cases')->nullable();

            // Additional Information
            $table->text('additional_notes')->nullable();

            // Workflow
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provider_registrations');
    }
};
