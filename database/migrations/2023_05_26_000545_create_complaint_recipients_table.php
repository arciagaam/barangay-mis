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
        Schema::create('complaint_recipients', function (Blueprint $table) {
            $table->foreignId('complaint_id')->nullable()->constrained('complaints')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('resident_id')->nullable()->constrained('residents')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('complaint_role_id')->nullable()->constrained('complaint_roles')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaint_recipients');
    }
};
