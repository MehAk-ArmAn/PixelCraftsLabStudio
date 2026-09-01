<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_channels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('label')->nullable();
            $table->text('description')->nullable();
            $table->string('accent', 16)->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });

        Schema::create('growth_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->text('full_description')->nullable();
            $table->string('ideal_for')->nullable();
            $table->string('duration')->nullable();
            $table->string('price_text')->nullable();
            $table->string('starting_price')->nullable();
            $table->string('billing_period')->nullable();
            $table->string('currency', 8)->nullable();
            $table->string('highlight_text')->nullable();
            $table->string('cta_label')->nullable();
            $table->string('cta_url')->nullable();
            $table->string('accent', 16)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->timestamps();
        });

        Schema::create('growth_plan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('growth_plan_id')->constrained('growth_plans')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });

        Schema::create('marketing_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->string('client_name')->nullable();
            $table->string('campaign_type')->nullable();
            $table->string('goal')->nullable();
            $table->date('starts_on')->nullable();
            $table->date('ends_on')->nullable();
            // planning | active | completed | paused | archived
            $table->string('status', 24)->default('planning')->index();
            $table->text('summary')->nullable();
            $table->text('strategy')->nullable();
            $table->text('creative_approach')->nullable();
            $table->text('results')->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('channel_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_channel_id')->constrained('marketing_channels')->cascadeOnDelete();
            $table->string('assignable_type');
            $table->unsignedBigInteger('assignable_id');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index(['assignable_type', 'assignable_id'], 'channel_assignable_idx');
            $table->unique(
                ['marketing_channel_id', 'assignable_type', 'assignable_id'],
                'channel_assignment_unique'
            );
        });

        Schema::create('project_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->string('metric_label');
            $table->string('metric_value');
            $table->string('metric_context')->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_metrics');
        Schema::dropIfExists('channel_assignments');
        Schema::dropIfExists('marketing_campaigns');
        Schema::dropIfExists('growth_plan_items');
        Schema::dropIfExists('growth_plans');
        Schema::dropIfExists('marketing_channels');
    }
};
