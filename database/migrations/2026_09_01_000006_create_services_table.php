<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('stage', 64)->nullable()->index();
            // 'build' = studio/product capabilities, 'growth' = marketing & growth
            $table->string('track', 32)->default('build')->index();
            $table->string('group', 64)->nullable()->index();
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->string('tag')->nullable();
            $table->text('body')->nullable();
            $table->text('long_body')->nullable();
            $table->string('caption')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_published')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('show_on_homepage')->default(true);
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
