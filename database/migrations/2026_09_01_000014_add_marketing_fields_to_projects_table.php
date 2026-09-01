<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->boolean('is_marketing_case_study')->default(false)->after('is_featured');
            $table->string('client_goal')->nullable()->after('case_study');
            $table->text('challenge')->nullable()->after('client_goal');
            $table->text('audience')->nullable()->after('challenge');
            $table->text('strategy')->nullable()->after('audience');
            $table->text('approach')->nullable()->after('strategy');
            $table->text('deliverables')->nullable()->after('approach');
            $table->text('results')->nullable()->after('deliverables');
            $table->text('lessons')->nullable()->after('results');
            $table->string('campaign_period')->nullable()->after('lessons');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'is_marketing_case_study', 'client_goal', 'challenge', 'audience',
                'strategy', 'approach', 'deliverables', 'results', 'lessons', 'campaign_period',
            ]);
        });
    }
};
