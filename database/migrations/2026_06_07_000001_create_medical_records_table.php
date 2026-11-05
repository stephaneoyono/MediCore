<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
            $table->string('patient_email')->index();
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->foreignId('prescription_id')->nullable()->constrained('prescriptions')->nullOnDelete();
            $table->string('title');
            $table->text('summary')->nullable();
            $table->date('record_date');
            $table->timestamps();

            $table->index(['doctor_id', 'record_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
