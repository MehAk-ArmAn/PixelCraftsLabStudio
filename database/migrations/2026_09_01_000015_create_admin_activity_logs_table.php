<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_name')->nullable();
            $table->string('action', 64)->index();
            $table->string('resource_type', 96)->nullable()->index();
            $table->unsignedBigInteger('resource_id')->nullable();
            $table->string('description')->nullable();
            $table->json('changes')->nullable();
            $table->timestamps();
        });

        Schema::create('content_revisions', function (Blueprint $table) {
            $table->id();
            $table->string('revisionable_type', 96);
            $table->unsignedBigInteger('revisionable_id');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_name')->nullable();
            $table->json('payload');
            $table->string('summary')->nullable();
            $table->timestamps();
            $table->index(['revisionable_type', 'revisionable_id'], 'revisionable_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_revisions');
        Schema::dropIfExists('admin_activity_logs');
    }
};
