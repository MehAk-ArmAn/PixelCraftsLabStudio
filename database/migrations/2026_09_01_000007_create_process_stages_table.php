<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('process_stages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64);
            $table->string('slug')->unique();
            $table->string('number', 8)->nullable();
            // 'build' = studio delivery process, 'growth' = marketing process
            $table->string('track', 32)->default('build')->index();
            $table->text('body')->nullable();
            $table->string('accent', 16)->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('process_stages');
    }
};
