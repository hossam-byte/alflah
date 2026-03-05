<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 10, 3);          // الكمية المباعة
            $table->decimal('unit_price', 10, 2);        // سعر البيع
            $table->decimal('purchase_price', 10, 2);    // سعر الشراء (لحساب الربح)
            $table->decimal('total_price', 12, 2);       // إجمالي البند
            $table->decimal('profit', 12, 2)->default(0); // ربح هذا البند
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
