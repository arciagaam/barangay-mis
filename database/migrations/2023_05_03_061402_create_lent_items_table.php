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
        Schema::create('lent_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->nullable()->constrained('residents')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('inventory_id')->nullable()->constrained('inventory')->cascadeOnUpdate()->nullOnDelete();
            $table->tinyInteger('status')->default(0);
            $table->integer('quantity');
            $table->string('contact');
            $table->date('return_date');
            $table->string('remarks')->nullable()->default('');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lent_items');
    }
};
