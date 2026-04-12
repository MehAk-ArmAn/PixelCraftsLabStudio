<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('particles');
    }

    public function down(): void
    {
        Schema::create('particles', function ($table) {
            $table->id();
            $table->string('image_path');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }
};
