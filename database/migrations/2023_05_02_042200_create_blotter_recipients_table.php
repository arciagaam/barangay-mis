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
        Schema::create('blotter_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blotter_id')->nullable()->constrained('blotters')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('resident_id')->nullable()->constrained('residents')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('blotter_role_id')->nullable()->constrained('blotter_roles')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blotter_recipients');
    }
};
