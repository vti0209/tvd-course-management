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
    // 1. Bảng Users (Đã sửa để tương thích với Laravel Auth)
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');
        $table->enum('role', ['admin', 'user'])->default('user');
        $table->string('avatar')->default('default-avatar.png');
        $table->timestamps(); // Thay cho created_at thủ công
    });

    // 2. Bảng Categories
    Schema::create('categories', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug')->unique();
        $table->text('description')->nullable();
        $table->timestamps();
    });

    // 3. Bảng Courses
    Schema::create('courses', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
        $table->string('title');
        $table->string('slug')->unique();
        $table->text('description')->nullable();
        $table->decimal('price', 15, 2)->default(0.00);
        $table->string('thumbnail')->nullable();
        $table->enum('status', ['draft', 'published'])->default('published');
        $table->timestamps();
    });

    // 4. Bảng Lessons
    Schema::create('lessons', function (Blueprint $table) {
        $table->id();
        $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
        $table->string('title');
        $table->string('video_url')->nullable();
        $table->enum('video_type', ['youtube', 'local'])->default('youtube');
        $table->integer('sort_order')->default(0);
        $table->timestamps();
    });

    // 5. Bảng trung gian course_user (Nhiều - Nhiều)
    Schema::create('course_user', function (Blueprint $table) {
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
        $table->timestamp('enrolled_at')->useCurrent();
        $table->enum('status', ['active', 'completed'])->default('active');
        $table->primary(['user_id', 'course_id']);
    });

    // 6. Bảng Sessions (cho Laravel session management)
    Schema::create('sessions', function (Blueprint $table) {
        $table->string('id')->primary();
        $table->foreignId('user_id')->nullable()->index()->constrained('users')->onDelete('cascade');
        $table->ipAddress('ip_address')->nullable();
        $table->text('user_agent')->nullable();
        $table->longText('payload');
        $table->integer('last_activity')->index();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('course_user');
        Schema::dropIfExists('lessons');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('users');
    }
};