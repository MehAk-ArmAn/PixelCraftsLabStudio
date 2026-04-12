<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            if (!Schema::hasColumn('contacts', 'service')) {
                $table->string('service')->nullable()->after('subject'); // selected service
            }

            if (!Schema::hasColumn('contacts', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('message'); // admin read state
            }
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            if (Schema::hasColumn('contacts', 'service')) {
                $table->dropColumn('service');
            }

            if (Schema::hasColumn('contacts', 'is_read')) {
                $table->dropColumn('is_read');
            }
        });
    }
};
