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
        Schema::create('remotes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->date('date_remote')->nullable()->comment('ngày remote');
            $table->time('start_time')->nullable()->comment('bắt đầu remote');
            $table->time('end_time')->nullable()->comment('nghỉ remote');
            $table->string('file_remote')->nullable()->comment('file remote');
            $table->string('approved_by')->nullable()->comment('người duyệt');
            $table->integer('status')->nullable()->comment('tình trạng');
            $table->string('description')->comment('mô tả');
            $table->string('file_image_remote')->nullable()->comment('file hịhf ảnh remote');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remotes');
    }
};
