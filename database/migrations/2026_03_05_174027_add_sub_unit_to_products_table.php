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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('has_sub_units')->default(false)->after('unit'); // هل المنتج له تجزئة؟
            $table->string('sub_unit_name')->nullable()->after('has_sub_units'); // اسم وحدة التجزئة (مثلاً: كيلو)
            $table->decimal('items_per_unit', 10, 3)->default(1)->after('sub_unit_name'); // معامل التحويل (مثلاً: 50 كيلو في الشكارة)
            $table->decimal('sub_unit_sale_price', 10, 2)->default(0)->after('items_per_unit'); // سعر بيع وحدة التجزئة
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['has_sub_units', 'sub_unit_name', 'items_per_unit', 'sub_unit_sale_price']);
        });
    }
};
