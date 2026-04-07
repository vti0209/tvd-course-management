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
        Schema::table('courses', function (Blueprint $table) {
            // Thêm các cột mới nếu chưa có
            if (!Schema::hasColumn('courses', 'name')) {
                $table->string('name')->nullable()->after('title');
            }
            if (!Schema::hasColumn('courses', 'duration')) {
                $table->integer('duration')->nullable()->after('price');
            }
            if (!Schema::hasColumn('courses', 'level')) {
                $table->enum('level', ['beginner', 'intermediate', 'advanced'])->nullable()->after('duration');
            }
            if (!Schema::hasColumn('courses', 'image')) {
                $table->string('image')->nullable()->after('thumbnail');
            }
            if (!Schema::hasColumn('courses', 'active')) {
                $table->boolean('active')->default(true)->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'duration',
                'level',
                'image',
                'active',
            ]);
        });
    }
};