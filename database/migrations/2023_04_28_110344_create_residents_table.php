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
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('nickname')->nullable();
            $table->tinyInteger('sex');
            $table->foreignId('gender_id')->nullable()->constrained('genders')->default('')->cascadeOnUpdate()->nullOnDelete();
            $table->date('birth_date');
            $table->integer('age');
            $table->string('place_of_birth');
            $table->foreignId('civil_status_id')->nullable()->constrained('civil_status')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('occupation_id')->nullable()->constrained('occupations')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('religion_id')->nullable()->constrained('religions')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('household_id')->nullable()->constrained('households')->cascadeOnUpdate()->nullOnDelete();
            $table->string('phone_number')->nullable();
            $table->string('telephone_number')->nullable();
            $table->tinyInteger('voter_status');
            $table->tinyInteger('disabled');
            $table->tinyInteger('single_parent');
            $table->tinyInteger('archived')->default(0);
            $table->foreignId('archive_reason_id')->nullable()->constrained('archive_reasons')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
