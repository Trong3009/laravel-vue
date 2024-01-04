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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->comment('id account');
            $table->string('code', 30);
            $table->string('name', 150);
            $table->string('email');
            $table->string('phone', 15);
            $table->string('avatar', 255)->nullable();
            $table->date('birthday');
            $table->tinyInteger('gender')->nullable()->comment('0: nam, 1: nu');
            $table->string('permanent_address', 255)->nullable()->comment('dia chi thuong tru');
            $table->string('residence_address', 255)->nullable()->comment('dia chi tam tru');
            $table->string('place_of_birth', 255)->nullable()->comment('noi sinh');
            $table->string('domicile', 255)->nullable()->comment('nguyen quan');
            $table->string('nationality', 255)->nullable()->comment('quoc tich');
            $table->string('nation', 255)->nullable()->comment('dan toc');
            $table->string('religion', 255)->nullable()->comment('ton giao');
            $table->tinyInteger('marital_status')->nullable()->comment('0:doc than, 1:da ket hon');

            $table->string('name_father', 150)->nullable()->comment('ten bo');
            $table->string('name_mother', 150)->nullable()->comment('ten me');
            $table->string('name_wife_husband', 150)->nullable()->comment('ten vo hoac chong');
            $table->string('birthday_father', 15)->nullable()->comment('nam sinh bo');
            $table->string('birthday_mother', 15)->nullable()->comment('nam sinh me');
            $table->string('birthday_wife_husband', 15)->nullable()->comment('nam sinh vo hoac chong');
            $table->text('note_user')->nullable()->comment('ghi chu tt user');

            //TT nguoi lien lac
            $table->string('person_contact', 150)->nullable()->comment('nguoi lien lac');
            $table->string('person_address', 255)->nullable()->comment('dia chi nguoi lien lac');
            $table->string('person_phone', 255)->nullable()->comment('SDT nguoi lien lac');
            $table->string('person_email', 150)->nullable()->comment('email nguoi lien lac');

            $table->string('vehicle_type', 50)->nullable()->comment('loai phuong tien');
            $table->string('vehicle_name', 150)->nullable()->comment('ten phuong tien');
            $table->string('vehicle_number', 10)->nullable()->comment('bien so');

            $table->tinyInteger('type_of_documents')->nullable()->comment('0: CCCD, 1: HC');
            $table->string('identity_card', 12)->nullable()->comment('so cccd ho chieu');
            $table->date('date_identity_card')->nullable()->comment('ngay cap cccd ho chieu');
            $table->string('address_identity_card', 255)->nullable()->comment('noi cap cccd ho chieu');

            //TT dang vien
            $table->boolean('is_member_communist')->nullable()->default(0)->comment('1: la dang vien');
            $table->string('number_member_communist', 50)->nullable()->comment('so the dang');
            $table->date('date_communist')->nullable()->comment('ngay ket nap');
            $table->string('address_communist', 255)->nullable()->comment('noi ket nap');

            //TT suc khoe
            $table->string('health_condition', 255)->nullable()->comment('tinh trang suc khoe');
            $table->string('height', 15)->nullable()->comment('chieu cao');
            $table->string('weight', 15)->nullable()->comment('can nang');
            $table->string('note_health_condition', 255)->nullable()->comment('ghi chu suc khoe');

            //thông tin thanh toán
            $table->string('bank_number', 30)->nullable()->comment('so tk bank');
            $table->string('bank_name', 150)->nullable()->comment('ten bank');
            $table->string('bank_branch', 150)->nullable()->comment('chi nhanh');
            $table->string('bank_account', 150)->nullable()->comment('chu tai khoan');

            // trình độ chuyên môn
            $table->string('transfer_level')->nullable()->comment('hoc ham hoc vi');
            $table->string('training_units', 255)->nullable()->comment('don vi dao tao');
            $table->string('specialize', 150)->nullable()->comment('chuyen nganh');

            $table->date('probation_day')->nullable()->comment('ngay thu viec');
            $table->date('official_day')->nullable()->comment('ngay chinh thuc');

            $table->string('position', 150)->nullable()->comment('chuc vu');
            $table->string('job_title', 150)->nullable()->comment('chuc danh');
            $table->string('work_note', 255)->nullable()->comment('ghi chu work');

            // Thong tin hop dong
//            $table->string('contract_number', 50)->nullable()->comment('ma hop dong');
//            $table->string('contract_type', 150)->nullable()->comment('loai hop dong');
//            $table->date('sign_date')->nullable()->comment('ngay ky hop dong');
//            $table->string('contract_term', 15)->nullable()->comment('thoi han hop dong');
//            $table->date('contract_start_date')->nullable()->comment('ngay bat dau hop dong');
//            $table->date('contract_end_date')->nullable()->comment('ngay ket thuc hop dong');
//            $table->string('contract_note', 255)->nullable()->comment('ghi chu hop dong');

            // trang thai lam viec
            $table->tinyInteger('work_status')->nullable()->default(1)->comment('1:dang lv, 2:da nghi viec, 3:nghi tam thoi');
            $table->date('quit_date')->nullable()->comment('ngay nhi viec');
            $table->string('quit_reason', 255)->nullable()->comment('ghi chu trang thai lv');

            //luong
            $table->decimal('basic_salary', 14, 2)->nullable()->comment('luong co ban');
            $table->decimal('responsibility_salary', 14, 2)->nullable()->comment('luong trach nhiem');
            $table->decimal('meal_allowance', 14, 2)->nullable()->comment('phu cap tien an');
            $table->decimal('travel_allowance', 14, 2)->nullable()->comment('phu cap xang xe');
            $table->decimal('insurance_salary', 14, 2)->nullable()->comment('muc luong dong BH');
            $table->decimal('insurance_amount', 14, 2)->nullable()->comment('muc dong BH');

            // thong tin bao hiem
//            $table->string('insurance_number', 255)->nullable()->comment('ma so BHXH');
//            $table->string('insurance_book_number', 255)->nullable()->comment('so so BH');
//            $table->date('start_insurance_date')->nullable()->comment('tham gia BH tu');
//            $table->string('contribution_ratio', 15)->nullable()->comment('ti le dong BHXH');
//            $table->string('province_code', 15)->nullable()->comment('ma tinh cap BHXH');
//            $table->string('health_insurance_card', 50)->nullable()->comment('so the BHXH');
//            $table->string('health_care_facility', 255)->nullable()->comment('noi dan ky kham');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
