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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category', 64)->index();
            $table->string('billing_type', 24)->default('custom')->index();
            $table->decimal('price', 12, 2)->nullable();
            $table->string('currency', 8)->default('AED');
            $table->string('billing_period', 64)->nullable();
            $table->boolean('is_starting_from')->default(false);
            $table->text('short_description')->nullable();
            $table->text('full_description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_recommended')->default(false);
            $table->string('badge')->nullable();
            $table->string('cta_label')->nullable();
            $table->string('cta_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_published')->default(true)->index();
            $table->decimal('original_price', 12, 2)->nullable();
            $table->decimal('promotional_price', 12, 2)->nullable();
            $table->string('promotion_label')->nullable();
            $table->text('terms')->nullable();
            $table->boolean('media_spend_separated')->default(false);
            $table->string('minimum_term')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->timestamps();
        });

        Schema::create('package_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->string('text');
            $table->string('group')->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_included')->default(true);
            $table->boolean('is_highlighted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_items');
        Schema::dropIfExists('packages');
    }
};
