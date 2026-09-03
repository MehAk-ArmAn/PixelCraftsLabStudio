<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interactive_experiences', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('page', 32)->index();
            $table->string('section_key', 96)->index();
            $table->string('type', 48)->index();
            $table->boolean('enabled')->default(true)->index();
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->string('cta_label')->nullable();
            $table->string('cta_url')->nullable();
            $table->string('accent_preset', 32)->default('violet-orange');
            $table->decimal('intensity', 3, 2)->default(1);
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->json('settings')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interactive_experiences');
    }
};
