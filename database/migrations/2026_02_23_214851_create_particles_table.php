<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('particles', function (Blueprint $table) {
            $table->id(); // primary key
            $table->string('image_path'); // column name to store image path
            $table->boolean('active')->default(true); // toggle on/off
            $table->timestamps(); // created_at & updated_at
        });
    }
        /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('particles');
    }
};
