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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('status_id')->nullable()->default(2)->constrained('blotter_status')->cascadeOnUpdate()->nullOnDelete();
            $table->string('incident_type');
            $table->string('details')->nullable()->default('');
            $table->string('incident_place');
            $table->dateTime('date_time_reported')->useCurrent();
            $table->dateTime('date_time_incident');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
