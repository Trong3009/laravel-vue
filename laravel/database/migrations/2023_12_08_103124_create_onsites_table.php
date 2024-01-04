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
        Schema::create('onsites', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->date('date_onsite')->nullable()->comment('ngày onsite');
            $table->time('start_time')->nullable()->comment('bắt đầu onsite');
            $table->time('end_time')->nullable()->comment('nghỉ ónite');
            $table->string('file_onsite')->nullable()->comment('file onsite');
            $table->string('location')->comment('địa điểm onsite');
            $table->string('object')->nullable()->comment('file dự án');
            $table->string('approved_by')->nullable()->comment('người duyệt');
            $table->integer('status')->nullable()->comment('tình trạng');
            $table->string('description')->nullable()->comment('mô tả');
            $table->string('file_image_onsite')->nullable()->comment('file hình ảnh onsite');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onsites');
    }
};
