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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('national_code',10)->unique()->index();
            $table->string('first_name',100);
            $table->string('last_name',100);
            $table->string('father_name',100);
            $table->date('birth_date');
            $table->enum('gender',['male','female']);
            $table->string('mobile',11)->unique();
            $table->string('postal_code', 10);
            $table->text('address');
            $table->softDeletes();
            $table->timestamps();
        });
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
