<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            $table->string('icon_image')->nullable()->after('primary_image');
            $table->string('feature_image')->nullable()->after('icon_image');
            $table->boolean('is_ecosystem_head')->default(false)->after('feature_image')->index();
        });

        $media = [
            'fikar' => ['assets/projects/fikar/hero.webp', 'assets/projects/fikar/feature-01.webp', 'assets/projects/fikar/icon.webp', false],
            'abandoned' => ['assets/projects/abandoned/hero.webp', null, 'assets/projects/abandoned/icon.webp', false],
            'farmcare' => ['assets/projects/farmcare/hero.webp', 'assets/projects/farmcare/feature-01.webp', 'assets/projects/farmcare/icon.webp', false],
            'studybuddy' => ['assets/projects/studybuddy/hero.webp', 'assets/projects/studybuddy/feature-01.webp', 'assets/projects/studybuddy/icon.webp', true],
            'bangtan' => ['assets/projects/bangtan/hero.webp', 'assets/projects/bangtan/feature-01.webp', 'assets/projects/bangtan/icon.webp', false],
            'matchmallow' => ['assets/projects/matchmallow/hero.webp', null, 'assets/projects/matchmallow/icon.webp', false],
            'coloriboo' => ['assets/projects/coloriboo/hero.webp', null, 'assets/projects/coloriboo/icon.webp', false],
            'mathibble' => ['assets/projects/mathibble/hero.webp', null, 'assets/projects/mathibble/icon.webp', false],
            'animal' => ['assets/projects/animal/hero.webp', null, 'assets/projects/animal/icon.webp', false],
            'bloxabet' => ['assets/projects/bloxabet/hero.webp', null, 'assets/projects/bloxabet/icon.webp', false],
            'globepop' => ['assets/projects/globepop/hero.webp', null, 'assets/projects/globepop/icon.webp', false],
            'alphablock' => ['assets/projects/alphablock/hero.webp', 'assets/projects/alphablock/feature-01.webp', 'assets/projects/alphablock/icon.webp', false],
            'pulse' => ['assets/projects/pulse/hero.webp', null, 'assets/projects/pulse/icon.webp', false],
        ];

        foreach ($media as $slug => [$hero, $feature, $icon, $ecosystemHead]) {
            DB::table('projects')->where('slug', $slug)->whereNull('primary_image')->update([
                'primary_image' => $hero,
            ]);
            DB::table('projects')->where('slug', $slug)->update([
                'feature_image' => $feature,
                'icon_image' => $icon,
                'is_ecosystem_head' => $ecosystemHead,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            $table->dropIndex(['is_ecosystem_head']);
            $table->dropColumn(['icon_image', 'feature_image', 'is_ecosystem_head']);
        });
    }
};
