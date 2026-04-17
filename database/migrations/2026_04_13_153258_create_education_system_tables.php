<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    // Xóa bảng cũ nếu tồn tại để tránh lỗi trùng lặp
    Schema::disableForeignKeyConstraints();
    Schema::dropIfExists('lessons');
    Schema::dropIfExists('chapters');
    Schema::dropIfExists('enrollments');
    Schema::dropIfExists('courses');
    Schema::dropIfExists('categories');
    Schema::dropIfExists('users');
    Schema::enableForeignKeyConstraints();

    // 1. Bảng Users
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('username', 50)->unique();
        $table->string('password');
        $table->string('email', 100)->unique();
        $table->string('full_name', 100)->nullable();
        $table->string('phone', 20)->nullable();
        $table->string('avatar')->nullable();
        $table->enum('role', ['user', 'provider', 'admin'])->default('user');
        $table->enum('status', ['pending', 'active', 'blocked'])->default('active');
        $table->timestamps();
    });

    // 2. Bảng Categories
    Schema::create('categories', function (Blueprint $table) {
        $table->id();
        $table->string('name', 100);
        $table->text('description')->nullable();
        $table->string('slug', 100)->unique();
        $table->timestamps();
    });

    // 4. Bảng Courses
    Schema::create('courses', function (Blueprint $table) {
        $table->id();
        $table->foreignId('provider_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
        $table->string('title');
        $table->text('description')->nullable();
        $table->decimal('price', 10, 2)->default(0);
        $table->string('thumbnail')->nullable();
        $table->enum('status', ['pending', 'active', 'rejected'])->default('pending');
        $table->timestamps();
    });

    // 5. Bảng Chapters
    Schema::create('chapters', function (Blueprint $table) {
        $table->id();
        $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
        $table->string('title');
        $table->integer('sort_order')->default(0);
        $table->timestamps();
    });

    // 6. Bảng Lessons
    Schema::create('lessons', function (Blueprint $table) {
        $table->id();
        $table->foreignId('chapter_id')->constrained('chapters')->onDelete('cascade');
        $table->string('title');
        $table->enum('content_type', ['video', 'pdf', 'link'])->default('video');
        $table->string('content_url');
        $table->integer('sort_order')->default(0);
        $table->timestamps();
    });
    
    // 7. Bảng Enrollments (Lưu vết mua khóa học)
    Schema::create('enrollments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
        $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
        $table->decimal('price_at_purchase', 10, 2);
        $table->timestamp('enrolled_at')->useCurrent();
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_system_tables');
    }
};