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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('nationality');
            $table->double('salary',8, 3);
            $table->date('birthday');
            $table->string('passport_no')->nullable();
            $table->date('passport_expiry')->nullable();
            $table->string('citizen_no')->nullable();
            $table->date('citizen_expiry')->nullable();
            $table->date('join_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
