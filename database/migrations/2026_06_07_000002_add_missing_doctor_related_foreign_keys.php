<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->index('user_id', 'doctors_user_id_index');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->index('doctor_id', 'schedules_doctor_id_index');
        });

        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement('ALTER TABLE doctors ADD CONSTRAINT doctors_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE schedules ADD CONSTRAINT schedules_doctor_id_foreign FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE');
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('schedules', function (Blueprint $table) {
                $table->dropIndex('schedules_doctor_id_index');
            });

            Schema::table('doctors', function (Blueprint $table) {
                $table->dropIndex('doctors_user_id_index');
            });

            return;
        }

        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign('schedules_doctor_id_foreign');
            $table->dropIndex('schedules_doctor_id_index');
        });

        Schema::table('doctors', function (Blueprint $table) {
            $table->dropForeign('doctors_user_id_foreign');
            $table->dropIndex('doctors_user_id_index');
        });
    }
};
