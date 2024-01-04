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
        Schema::create('over_time_votes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->date('date_ot')->nullable()->comment('ngày ot');
            $table->string('object');
            $table->string('date_type')->nullable();
            $table->string('shift')->nullable()->comment('ca ngày/đêm');
            $table->time('start_time')->nullable()->comment('thời gian bắt đầu');
            $table->time('end_time')->nullable()->comment('thời gian kết thúc');
            $table->string('total_time')->nullable()->comment('tổng thời gian');
            $table->string('multi_time')->nullable()->comment('Phần trăm nhận lương');
            $table->integer('total_multi')->nullable()->comment('hệ số tính lương');
            $table->string('client')->nullable();
            $table->string('description')->nullable();
            $table->integer('status')->nullable();
            $table->string('approved_by_user')->nullable()->comment('người duyệt');
            $table->string('reason_approval')->nullable()->comment('lý do duyệt');
            $table->dateTime('approved_time')->nullable()->comment('thời gian duyệt');
            $table->string('report_ot')->nullable()->comment('ghi chú');
            $table->string('user_details_id')->nullable()->comment('id hồ sơ');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('over_time_votes');
    }
};
