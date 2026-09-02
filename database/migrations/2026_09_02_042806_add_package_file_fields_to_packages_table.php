<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Builder;
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
        Schema::table('packages', function (Blueprint $table) {
            $table->string('public_name')->nullable()->after('name');
            $table->string('internal_code', 64)->nullable()->unique()->after('slug');
            $table->string('price_presentation', 24)->default('estimated')->after('is_starting_from');
            $table->decimal('minimum_fee', 12, 2)->nullable()->after('promotional_price');
            $table->boolean('promotion_eligible')->default(true)->after('promotion_label');
            $table->boolean('founding_eligible')->default(true)->after('promotion_eligible');
            $table->json('package_scope')->nullable()->after('seo_description');
            $table->json('internal_details')->nullable()->after('package_scope');
        });

        DB::table('packages')
            ->where(function (Builder $query): void {
                $query->where('billing_type', 'custom')->orWhereNull('price');
            })
            ->update(['price_presentation' => 'custom']);

        DB::table('packages')
            ->where('is_starting_from', true)
            ->where('price_presentation', '!=', 'custom')
            ->update(['price_presentation' => 'estimated_from']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn([
                'public_name',
                'internal_code',
                'price_presentation',
                'minimum_fee',
                'promotion_eligible',
                'founding_eligible',
                'package_scope',
                'internal_details',
            ]);
        });
    }
};
