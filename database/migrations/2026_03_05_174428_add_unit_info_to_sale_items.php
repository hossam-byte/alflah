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
        Schema::table('sale_items', function (Blueprint $table) {
            $table->string('unit_name')->nullable()->after('quantity'); // اسم الوحدة وقت البيع (شكارة/كيلو)
            $table->boolean('is_sub_unit')->default(false)->after('unit_name'); // هل البيع تم بالتجزئة؟
            $table->decimal('items_per_unit', 10, 3)->default(1)->after('is_sub_unit'); // معامل التحويل وقت البيع
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropColumn(['unit_name', 'is_sub_unit', 'items_per_unit']);
        });
    }
};
