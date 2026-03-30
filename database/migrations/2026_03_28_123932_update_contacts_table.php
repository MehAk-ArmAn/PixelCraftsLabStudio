<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            if (!Schema::hasColumn('contacts', 'email')) {
                $table->string('email')->nullable();
            }

            if (!Schema::hasColumn('contacts', 'phone')) {
                $table->string('phone')->nullable();
            }

            if (!Schema::hasColumn('contacts', 'subject')) {
                $table->string('subject')->nullable();
            }

            if (!Schema::hasColumn('contacts', 'message')) {
                $table->text('message')->nullable();
            }

            // DO NOT add name again if it already exists
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            //
        });
    }
};
