<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_options', function (Blueprint $table) {
            $table->id();
            // build | scope | timeline | service | budget
            $table->string('type', 32)->index();
            $table->string('label');
            $table->string('value');
            $table->string('group', 64)->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });

        Schema::create('contact_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('build_type')->nullable();
            $table->string('scope')->nullable();
            $table->string('timeline')->nullable();
            $table->string('service')->nullable();
            $table->string('budget')->nullable();
            $table->text('message')->nullable();
            // Optional marketing/growth enquiry detail
            $table->string('business_name')->nullable();
            $table->string('website_url')->nullable();
            $table->string('social_platforms')->nullable();
            $table->string('primary_goal')->nullable();
            $table->string('target_audience')->nullable();
            $table->text('current_marketing')->nullable();
            $table->string('preferred_channels')->nullable();
            $table->boolean('is_marketing_enquiry')->default(false)->index();
            $table->string('status', 24)->default('new')->index();
            $table->text('admin_notes')->nullable();
            $table->string('ip_address', 64)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_submissions');
        Schema::dropIfExists('contact_options');
    }
};
