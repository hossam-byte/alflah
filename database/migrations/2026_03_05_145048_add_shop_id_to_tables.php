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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('shop_id')->nullable()->constrained()->onDelete('cascade');
            $table->boolean('is_super_admin')->default(false);
        });

        $tables = [
            'categories',
            'products',
            'customers',
            'suppliers',
            'sales',
            'purchases',
            'expenses',
            'sale_items',
            'purchase_items'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('shop_id')->nullable()->constrained()->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('shop_id');
            $table->dropColumn('is_super_admin');
        });

        $tables = [
            'categories',
            'products',
            'customers',
            'suppliers',
            'sales',
            'purchases',
            'expenses',
            'sale_items',
            'purchase_items'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropConstrainedForeignId('shop_id');
            });
        }
    }
};
