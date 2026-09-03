<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_featured_projects', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('project_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('slot')->index();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_primary')->default(false)->index();
            $table->boolean('enabled')->default(true)->index();
            $table->string('display_mode', 32)->default('auto');
            $table->string('media_mode', 32)->default('auto');
            $table->string('badge_text')->nullable();
            $table->string('cta_label')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        $projectIds = DB::table('projects')
            ->whereIn('slug', ['bangtan', 'studybuddy', 'alphablock'])
            ->pluck('id', 'slug');
        $now = now();

        foreach (['bangtan', 'studybuddy', 'alphablock'] as $index => $slug) {
            if (! isset($projectIds[$slug])) {
                continue;
            }

            DB::table('homepage_featured_projects')->insert([
                'project_id' => $projectIds[$slug],
                'slot' => $index + 1,
                'sort_order' => ($index + 1) * 10,
                'is_primary' => $index === 0,
                'enabled' => true,
                'display_mode' => 'auto',
                'media_mode' => 'auto',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_featured_projects');
    }
};
