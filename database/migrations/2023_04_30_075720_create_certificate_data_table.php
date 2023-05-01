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
        Schema::create('certificate_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificate_id')->nullable()->constrained('certificates')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('certificate_type_id')->nullable()->constrained('certificate_types')->cascadeOnUpdate()->nullOnDelete();
            $table->string('certificate_input_id');
            $table->string('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_data');
    }
};
