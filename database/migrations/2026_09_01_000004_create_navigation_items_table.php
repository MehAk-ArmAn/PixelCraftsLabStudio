<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('navigation_items', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('route_key', 64);
            $table->string('destination')->nullable();
            $table->string('number', 8)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->boolean('show_desktop')->default(true);
            $table->boolean('show_mobile')->default(true);
            $table->boolean('show_footer')->default(true);
            $table->boolean('is_external')->default(false);
            $table->boolean('open_new_tab')->default(false);
            $table->timestamps();
            $table->unique('route_key');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('navigation_items');
    }
};
