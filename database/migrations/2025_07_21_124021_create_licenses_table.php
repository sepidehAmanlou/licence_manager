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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->string('code',50)->unique();
            $table->string('title',200);
            $table->text('description');
            $table->string('issuer_organization_code',50);
            $table->unsignedSmallInteger('issue_duration_days');
            $table->unsignedSmallInteger('valid_duration_days');
            $table->decimal('issue_fee', 10, 2);
            $table->enum('status', ['active', 'inactive', 'archived'])->default('active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
