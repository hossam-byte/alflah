<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('owner_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('logo')->nullable();
            // Colors for dynamic theming
            $table->string('primary_color')->default('#2d7a18'); // Default Green
            $table->string('secondary_color')->default('#1a5c0a');
            $table->string('accent_color')->default('#c8a000'); // Gold
            $table->boolean('is_active')->default(false); // Developer activates this
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
