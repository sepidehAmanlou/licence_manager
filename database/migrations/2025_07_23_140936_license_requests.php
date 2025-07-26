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
        Schema::create('license_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_code')->unique()->nullable();
            $table->foreignId('user_id')->constrained('users','id')->cascadeOnDelete()->index();
            $table->foreignId('license_id')->constrained('licenses','id')->cascadeOnDelete();
            $table->string('business_postal_code', 10);
            $table->text('business_address');
            $table->enum('status',['pending','approved','rejected'])->default('pending')->index();
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->text('admin_note')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_requests');
    }
};
