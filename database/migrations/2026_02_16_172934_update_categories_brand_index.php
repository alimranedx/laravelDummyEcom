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
        if (Schema::hasColumn('categories', 'brand_id')) {
            // Drop foreign key if it exists (assuming standard naming convention)
            try {
                Schema::table('categories', function (Blueprint $table) {
                    $table->dropForeign(['brand_id']);
                });
            } catch (\Exception $e) {
                // Foreign key might not exist or other error, ignore safely 
                // as we just want to ensure it's gone or wasn't there
            }
        } else {
            Schema::table('categories', function (Blueprint $table) {
                $table->unsignedBigInteger('brand_id')->nullable()->after('id');
            });
        }

        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->unique(['brand_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['brand_id', 'slug']);
            $table->unique('slug');
            // We don't necessarily want to drop brand_id if it existed before, 
            // but for a clean rollback of this specific logic:
            if (Schema::hasColumn('categories', 'brand_id')) {
                 $table->dropColumn('brand_id');
            }
        });
    }
};
