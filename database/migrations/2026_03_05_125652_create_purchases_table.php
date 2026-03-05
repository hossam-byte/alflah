<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // رقم فاتورة الشراء
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
            $table->date('purchase_date');
            $table->decimal('total_amount', 12, 2)->default(0);   // إجمالي الفاتورة
            $table->decimal('paid_amount', 12, 2)->default(0);    // المبلغ المدفوع
            $table->decimal('discount', 10, 2)->default(0);       // الخصم
            $table->enum('payment_status', ['paid', 'partial', 'unpaid'])->default('paid');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
