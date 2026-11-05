<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hospital_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->string('title');
            $table->text('content');
            $table->date('note_date');
            $table->timestamps();

            $table->index(['doctor_id', 'note_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hospital_notes');
    }
};
