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
            // Thêm trường lưu lý do từ chối
            if (!Schema::hasColumn('courses', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('status');
            }
            
            // Thêm trường lưu ngày phê duyệt
            if (!Schema::hasColumn('courses', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('rejection_reason');
            }
            
            // Thêm trường lưu ID admin phê duyệt
            if (!Schema::hasColumn('courses', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete()->after('approved_at');
            }
            
            // Thêm trường lưu ngày từ chối
            if (!Schema::hasColumn('courses', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('approved_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Drop các trường đã thêm
            if (Schema::hasColumn('courses', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
            if (Schema::hasColumn('courses', 'approved_at')) {
                $table->dropColumn('approved_at');
            }
            if (Schema::hasColumn('courses', 'approved_by')) {
                $table->dropForeign(['approved_by']);
                $table->dropColumn('approved_by');
            }
            if (Schema::hasColumn('courses', 'rejected_at')) {
                $table->dropColumn('rejected_at');
            }
        });
    }
};