<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table): void {
            $table->string('cta_label')->nullable()->after('icon');
            $table->string('cta_url')->nullable()->after('cta_label');
        });

        DB::table('services')->where('slug', 'digital-marketing-growth')->update([
            'cta_label' => 'Explore marketing & growth',
            'cta_url' => '/marketing',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table): void {
            $table->dropColumn(['cta_label', 'cta_url']);
        });
    }
};
