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
        Schema::create('pause_votes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('since_session')->nullable()->comment('Nghỉ từ buổi');
            $table->string('todate_session')->nullable()->comment('Nghỉ đến buổi');
            $table->integer('number_days')->nullable()->comment('Số ngày nghỉ');
            $table->string('reason')->comment('Lý do nghỉ');
            $table->integer('status')->nullable()->comment('Trạng thái');
            $table->string('types_break')->nullable()->comment('Kiểu nghỉ');
            $table->string('salary_percentege')->nullable()->comment('Phần chăm lương nhận');
            $table->string('history_time')->nullable()->comment('Thời gian duyệt');
            $table->string('reason_approved')->nullable()->comment('Lý do duyệt');
            $table->string('approved_by')->nullable()->comment('Người duyệt');
            $table->date('since_date')->nullable()->comment('Ngày nghỉ từ');
            $table->date('todate_date')->nullable()->comment('Ngày nghỉ đến');
            $table->integer('users_detail_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pause_votes');
    }
};
