<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the old unique constraint
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'product_id']);
        });

        // Add new unique constraint that includes variant_id
        // SQLite allows multiple NULLs in unique constraints, so this will work correctly
        Schema::table('cart_items', function (Blueprint $table) {
            $table->unique(['user_id', 'product_id', 'variant_id'], 'cart_items_user_product_variant_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the new unique constraint
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropUnique('cart_items_user_product_variant_unique');
        });

        // Restore the old unique constraint
        Schema::table('cart_items', function (Blueprint $table) {
            $table->unique(['user_id', 'product_id']);
        });
    }
};
