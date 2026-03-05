<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');                          // اسم المنتج
            $table->string('barcode')->nullable()->unique(); // الباركود
            $table->string('unit')->default('كيلو');        // وحدة القياس: كيلو، لتر، عبوة، كيس...
            $table->decimal('purchase_price', 10, 2)->default(0); // سعر الشراء
            $table->decimal('sale_price', 10, 2)->default(0);     // سعر البيع
            $table->decimal('stock', 10, 3)->default(0);           // الكمية في المخزن
            $table->decimal('min_stock', 10, 3)->default(5);      // الحد الأدنى للتنبيه
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
