<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('category', 64)->default('Web')->index();
            $table->string('kind')->nullable();
            $table->string('platform')->nullable();
            $table->string('layout_size', 16)->default('std');
            $table->text('short_description')->nullable();
            $table->text('full_description')->nullable();
            $table->text('case_study')->nullable();
            $table->string('external_url')->nullable();
            $table->string('status', 24)->default('published')->index();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->boolean('is_archived')->default(false);
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->string('primary_image')->nullable();
            $table->json('gallery')->nullable();
            $table->string('initials', 8)->nullable();
            $table->string('primary_tint', 16)->nullable();
            $table->string('secondary_tint', 16)->nullable();
            $table->string('cta_label')->nullable();
            $table->string('cta_url')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('og_image')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
