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
        Schema::create('complaint_hearings', function (Blueprint $table) {
            $table->id();
            $table->integer('number');
            $table->foreignId('complaint_id')->nullable()->default(2)->constrained('complaints')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('status_id')->nullable()->default(2)->constrained('blotter_status')->cascadeOnUpdate()->nullOnDelete();
            $table->string('details')->default('');
            $table->date('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaint_hearings');
    }
};
