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
        Schema::create('late_votes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->date('late_date')->nullable()->comment('ngày xin muộn');
            $table->string('shift_job')->nullable()->comment('ca muộn');
            $table->time('start_time')->nullable()->comment('thời gian muộn');
            $table->time('end_time')->nullable()->comment('Thời gian kết thúc');
            $table->integer('number_minute')->nullable()->comment('số phút');
            $table->string('object')->nullable()->comment('dự án');
            $table->string('reason')->comment('lý do');
            $table->integer('status')->nullable()->comment('trạng thái');
            $table->string('approved_by')->nullable()->comment('người duyệt');
            $table->string('reason_approved')->nullable()->comment('lý do duyệt');
            $table->dateTime('time_approved')->nullable()->comment('thời gian duyệt');
            $table->string('user_details_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('late_votes');
    }
};
