<template>
  <q-dialog persistent>
    <q-card style="width: 1110px; max-width: 80vw">
      <q-card-section class="q-pb-none">
        <div class="q-container">
          <div class="form-group mb-10 pt-30">
            <h3 v-if="prop" class="text-grey-10 add__title">Cập nhật thông tin nhân viên</h3>
            <h3 v-else class="text-grey-10 add__title">Thêm mới nhân viên</h3>
          </div>
          <q-tabs
            v-model="tab"
            dense
            class="info-tabs text-grey-10"
            active-color="cyan-9"
            indicator-color="cyan-9"
            align="justify"
          >
            <q-tab name="mails" label="Thông tin cá nhân"></q-tab>
            <q-tab name="alarms" label="Thông tin làm việc"></q-tab>
          </q-tabs>
          <q-tab-panels v-model="tab" animated class="rounded-borders">
            <q-tab-panel name="mails">
              <div class="row">
                <div class="form-left__container col-7">
                  <div class="form_box mb-10">
                    <div class="form_top">
                      <h3>Thông tin chung</h3>
                      <div class="image-upload">
                        <img class="preview-image" :src="previewImage" alt="">
                        <div class="">
                            <q-file
                              v-model="files"
                              label="Avatar"
                              style="max-width: 300px;height: 59px;overflow: hidden; position: absolute; bottom: 0"
                            >
                              <template v-slot:prepend>
                                <q-icon name="attach_file" />
                              </template>
                            </q-file>
                        </div>
                      </div>
                      <div class="form_group d-flex mb-10">
                        <label for="employeecode"
                        >Mã nhân viên <span>*</span>:
                        </label>
                        <input type="text" id="employeecode"
                               v-model="employee.code"
                        />
                      </div>

                      <div class="form_group d-flex mb-10">
                        <label>Họ và tên <span>*</span>:</label>
                        <input type="text"
                               v-model="employee.name"
                        />
                      </div>

                      <div class="form_group d-flex mb-10">
                        <label>Ngày sinh <span>*</span>:</label>
                        <input type="date"
                               v-model="employee.birthday"
                        />
                      </div>

                      <div class="form_group d-flex mb-10">
                        <label>Nơi sinh:</label>
                        <input type="text" v-model="employee.place_of_birth"/>
                      </div>

                      <div class="form_group d-flex mb-10">
                        <label>Giới tính:</label>
                        <div class="input-radio">
                          <div class="input-options">
                            <input
                              type="radio"
                              name="gender"
                              value="0"
                              v-model="employee.gender"
                            />
                            <label for="boy">Nam</label>
                          </div>
                          <div class="input-options">
                            <input
                              type="radio"
                              name="gender"
                              value="1"
                              v-model="employee.gender"
                            />
                            <label for="girl">Nữ</label>
                          </div>
                          <div class="input-options">
                            <input
                              type="radio"
                              name="gender"
                              value="2"
                              v-model="employee.gender"
                            />
                            <label for="other">Khác</label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-center">
                      <div class="form_group d-flex mb-10">
                        <label>Địa chỉ thường trú:</label>
                        <input type="text"
                               v-model="employee.permanent_address"
                        />
                      </div>

                      <div class="form_group d-flex mb-10">
                        <label>Địa chỉ tạm quán:</label>
                        <input type="text"
                               v-model="employee.residence_address"
                        />
                      </div>

                      <div class="form_group d-flex mb-10">
                        <label>Nguyên quán:</label>
                        <input type="text"
                               v-model="employee.domicile"
                        />
                      </div>

                      <div class="form_group d-flex mb-10">
                        <label>Số điện thoại <span>*</span>:</label>
                        <input id="phonenumber" type="text"
                               v-model="employee.phone"
                        />
                      </div>

                      <div class="form_group d-flex mb-10">
                        <label for="email">Email <span>*</span>:</label>
                        <input id="email" type="text"
                               v-model="employee.email"
                        />
                      </div>
                    </div>
                    <div class="form-bottom">
                      <div class="form_group d-flex mb-10">
                        <label>Quốc tịch:</label>
                        <input id="email" type="text"
                               class="form-control"
                               v-model="employee.nationality"
                        />
                      </div>
                      <div class="form_group d-flex mb-10">
                        <label>Dân tộc:</label>
                        <input id="email" type="text"
                               class="form-control"
                               v-model="employee.nation"
                        />
                      </div>
                      <div class="form_group d-flex mb-10">
                        <label>Tôn giáo:</label>
                        <input id="email" type="text"
                               class="form-control"
                               v-model="employee.religion"
                        />
                      </div>
                      <div class="form_group d-flex mb-10">
                        <label for="namedad">Họ tên cha:</label>
                        <div class="select-options">
                          <input id="name_father" type="text"
                                 v-model="employee.name_father"
                          />
                          <div class="year-birthday">
                            <label for="yearbirthday">Năm sinh:</label>
                            <input id="yearbirthday" type="text"
                                   v-model="employee.birthday_father"
                            />
                          </div>
                        </div>
                      </div>
                      <div class="form_group d-flex mb-10">
                        <label for="namemom">Họ tên mẹ:</label>
                        <div class="select-options">
                          <input id="namemom" type="text"
                                 v-model="employee.name_mother"
                          />
                          <div class="year-birthday">
                            <label for="yearbirthdaymom">Năm sinh:</label>
                            <input id="yearbirthdaymom" type="text"
                                   v-model="employee.birthday_mother"
                            />
                          </div>
                        </div>
                      </div>
                      <div class="form_group d-flex mb-10">
                        <label>Tình trạng hôn nhân:</label>
                        <div class="select-options">
                          <select name="lang" id="lang-select" v-model="employee.marital_status">
                            <option value="0" selected>Độc thân</option>
                            <option value="1" selected>Đã kết hôn</option>
                          </select>
                        </div>
                      </div>
                      <div class="form_group d-flex mb-10">
                        <label for="namecouple">Họ tên vợ (chồng):</label>
                        <div class="select-options">
                          <input id="namecouple" type="text"
                                 v-model="employee.name_wife_husband"
                          />
                          <div class="year-birthday">
                            <label for="yearbirthdaycouple"
                            >Năm sinh:</label
                            >
                            <input id="yearbirthdaycouple" type="text"
                                   v-model="employee.birthday_wife_husband"
                            />
                          </div>
                        </div>
                      </div>
                      <div class="form-center">
                        <div class="form_group d-flex mb-10">
                          <label for="note">Ghi chú:</label>
                          <input id="note" type="text" v-model="employee.note_user"/>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form_box mb-10">
                    <h3>Thông tin liên lạc</h3>
                    <div class="form-center">
                      <div class="form_group d-flex mb-10">
                        <label for="person_contact">Người liên hệ:</label>
                        <input id="person_contact" type="text"
                               v-model="employee.person_contact"
                        />
                      </div>
                      <div class="form_group d-flex mb-10">
                        <label for="person_address">Địa chỉ:</label>
                        <input type="text"
                               v-model="employee.person_address"
                        />
                      </div>
                      <div class="form_group d-flex mb-10">
                        <label for="person_phone">Số điện thoại:</label>
                        <input id="person_phone" type="text"
                               v-model="employee.person_phone"
                        />
                      </div>
                      <div class="form_group d-flex mb-10">
                        <label for="person_email">Email:</label>
                        <input id="person_email" type="text"
                               v-model="employee.person_email"
                        />
                      </div>
                    </div>
                  </div>

                  <div class="form_box">
                    <h3>Phương tiện di chuyển</h3>
                    <div class="form-center">
                      <div class="form_group d-flex mb-10">
                        <label>Loại phương tiện</label>
                        <input class="form-control" type="text"
                               v-model="employee.vehicle_type"
                        />
                      </div>
                      <div class="form_group d-flex mb-10">
                        <label for="person_address">Tên phương tiện:</label>
                        <input class="form-control" type="text"
                               v-model="employee.vehicle_name"
                        />
                      </div>
                      <div class="form_group d-flex mb-10">
                        <label for="person_phone">Biển kiểm soát:</label>
                        <input class="form-control" type="text"
                               v-model="employee.vehicle_number"
                        />
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-right__container col-5 pl-30">
                  <div class="form_box mb-10">
                    <h3>Căn cước công dân/Hộ chiếu:</h3>
                    <div class="id-card">
                      <div class="form_group d-flex mb-10">
                        <label for="type_of_documents">Loại giấy tờ:</label>
                        <select name="type_of_documents" id="type_of_documents"
                                class="form-control"
                                v-model="employee.type_of_documents"
                        >
                          <option value="0" selected>CCCD</option>
                          <option value="1" selected>Hộ chiếu</option>
                        </select>
                      </div>

                      <div class="form_group d-flex mb-10">
                        <label for="identification">Số CCCD/HC:</label>
                        <input
                          class="form-control"
                          id="identification"
                          type="text"
                          v-model="employee.identity_card"
                        />
                      </div>

                      <div class="form_group d-flex mb-10">
                        <label>Ngày cấp:</label>
                        <input class="form-control" type="date"
                               v-model="employee.date_identity_card"
                        />
                      </div>

                      <div class="form_group d-flex mb-10">
                        <label for="issuedby">Nơi cấp:</label>
                        <input
                          class="form-control"
                          id="issuedby"
                          type="text"
                          v-model="employee.address_identity_card"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="form_box mb-10">
                    <h3>Thông tin Đảng viên</h3>
                    <div class="id-card">
                      <div class="form_group d-flex mb-10">
                        <label>Là đảng viên:</label>
                        <div style="width: 55%">
                          <input
                            style="height: 20px; width: 20px"
                            type="checkbox" value="1"
                            v-model="employee.is_member_communist"
                          />
                        </div>
                      </div>
                      <div class="form_group d-flex mb-10">
                        <label for="partycard">Số thẻ đảng:</label>
                        <input
                          class="form-control"
                          id="partycard"
                          type="text"
                          v-model="employee.number_member_communist"
                        />
                      </div>

                      <div class="form_group d-flex mb-10">
                        <label>Ngày kết nạp:</label>
                        <input class="form-control" type="date"
                               v-model="employee.date_communist"
                        />
                      </div>

                      <div class="form_group d-flex mb-10">
                        <label for="admissionplace">Nơi kết nạp:</label>
                        <input
                          class="form-control"
                          id="admissionplace"
                          type="text"
                          v-model="employee.address_communist"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="form_box mb-10">
                    <h3>Tình trạng sức khỏe</h3>
                    <div class="id-card">
                      <div class="form_group d-flex mb-10">
                        <label for="health_condition"
                        >Tình trạng sức khỏe:</label
                        >
                        <input
                          class="form-control"
                          id="health_condition"
                          type="text"
                          v-model="employee.health_condition"
                        />
                      </div>

                      <div class="form_group d-flex mb-10">
                        <label for="height">Chiều cao:</label>
                        <input
                          class="form-control"
                          id="height"
                          type="text"
                          v-model="employee.height"
                        />
                      </div>

                      <div class="form_group d-flex mb-10">
                        <label for="weight">Cân nặng:</label>
                        <input
                          class="form-control"
                          id="weight"
                          type="text"
                          v-model="employee.weight"
                        />
                      </div>
                      <div class="form_group d-flex mb-10">
                        <label for="note_health_condition">Ghi chú:</label>
                        <input
                          class="form-control"
                          id="note_health_condition"
                          type="text"
                          v-model="employee.note_health_condition"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="form_box mb-10">
                    <h3>Thông tin thanh toán</h3>
                    <div class="id-card">
                      <div class="form_group d-flex mb-10">
                        <label for="bank_number">Số tài khoản:</label>
                        <input
                          class="form-control"
                          id="bank_number"
                          type="text"
                          v-model="employee.bank_number"
                        />
                      </div>

                      <div class="form_group d-flex mb-10">
                        <label for="bank_name">Ngân hàng:</label>
                        <input class="form-control" id="bank_name" type="text"
                               v-model="employee.bank_name"
                        />
                      </div>

                      <div class="form_group d-flex mb-10">
                        <label for="bank_branch">Chi nhánh:</label>
                        <input
                          class="form-control"
                          id="bank_branch"
                          type="text"
                          v-model="employee.bank_branch"
                        />
                      </div>

                      <div class="form_group d-flex mb-10">
                        <label for="bank_account">Chủ tài khoản:</label>
                        <input
                          class="form-control"
                          id="bank_account"
                          type="text"
                          v-model="employee.bank_account"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="form_box mb-10">
                    <h3>Trình độ chuyên môn</h3>
                    <div class="id-card">
                      <div class="form_group d-flex mb-10">
                        <label for="transfer_level">Học hàm, học vị:</label>
                        <input
                          class="form-control"
                          id="transfer_level"
                          type="text"
                          v-model="employee.transfer_level"
                        />
                      </div>
                      <div class="form_group d-flex mb-10">
                        <label for="training_units">Đơn vị đào tạo:</label>
                        <input
                          class="form-control"
                          id="training_units"
                          type="text"
                          v-model="employee.training_units"
                        />
                      </div>

                      <div class="form_group d-flex mb-10">
                        <label for="specialize">Chuyên ngành:</label>
                        <input
                          class="form-control"
                          id="specialize"
                          type="text"
                          v-model="employee.specialize"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </q-tab-panel>
            <q-tab-panel name="alarms">
              <div class="form_box mb-10">
                <h3>Thông tin làm việc</h3>
                <div class="form-info-work row">
                  <div class="col-6">
                    <div class="id-card_left">
                      <div class="form_group d-flex mb-10">
                        <label for="probation_day">Ngày thử việc:</label>
                        <input
                          class="form-control"
                          id="probation_day"
                          type="date"
                          v-model="employee.probation_day"
                        />
                      </div>
                      <div class="form_group d-flex mb-10">
                        <label for="position">Chức vụ:</label>
                        <div class="work-info">
                          <select name="position" id="position" v-model="employee.position">
                            <option value="" selected>Chọn chức vụ</option>
                            <option value="Giám đốc" selected>Giám đốc</option>
                            <option value="BA" selected>BA</option>
                            <option value="Tester" selected>Tester</option>
                            <option value="Dev" selected>Dev</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-6 pl-30">
                    <div class="id-card_right">
                      <div class="card-date_job">
                        <div class="form_group d-flex mb-10">
                          <label for="official_day">Ngày chính thức:</label>
                          <input class="form-date" type="date"
                                 v-model="employee.official_day"
                          />
                        </div>
                      </div>

                      <div class="form_group d-flex mb-10">
                        <label>Quản lí trực tiếp:</label>
                        <div class="work-info">
                          <select name="parent_id" v-model="employee.parent_id">
                            <option value="" selected>Chọn người quản lý</option>
                            <option value="1" selected>Nguyen Van A</option>
                            <option value="2" selected>Nguyen Van B</option>
                          </select>
                        </div>
                      </div>

                      <div class="form_group d-flex mb-10">
                        <label>Chức danh:</label>
                        <input name="job_title" type="text" class="form-control"
                               v-model="employee.job_title"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="card-note">
                    <div class="form_group d-flex mb-10">
                      <label for="work_note">Ghi chú:</label>
                      <input class="form-note" id="nodework" type="text"
                             v-model="employee.work_note"
                      />
                    </div>
                  </div>
                </div>
              </div>
              <div class="form_box mb-10">
                <h3>Trạng thái làm việc</h3>
                <div class="form-info-work row">
                  <div class="col-6">
                    <div class="id-card_left">
                      <div class="form_group d-flex mb-10">
                        <label>Trạng thái:</label>
                        <div class="work-info">
                          <select name="work_status" v-model="employee.work_status">
                            <option value="" selected>Chọn trạng thái</option>
                            <option value="1" selected>Đang làm việc</option>
                            <option value="2" selected>Đã nghỉ việc</option>
                            <option value="3" selected>Nghỉ tạm thời</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-6 pl-30">
                    <div class="card-date_job">
                      <div class="form_group d-flex mb-10">
                        <label>Ngày nghỉ việc:</label>
                        <input class="form-date" type="date"
                               v-model="employee.quit_date"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="card-note">
                    <div class="form_group d-flex mb-10">
                      <label>Ghi chú:</label>
                      <input
                        class="form-note"
                        type="text"
                        v-model="employee.quit_reason"
                      />
                    </div>
                  </div>
                </div>
              </div>
              <div class="form_box mb-10">
                <h3>Thông tin lương</h3>
                <div class="form-info-work row">
                  <div class="col-6">
                    <div class="id-card_left">
                      <div class="form_group d-flex mb-10">
                        <label>Lương cơ bản:</label>
                        <input
                          class="form-control"
                          type="text"
                          v-model="employee.basic_salary"
                        />
                      </div>

                      <div class="form_group d-flex mb-10">
                        <label>Phụ cấp ăn trưa:</label>
                        <input
                          class="form-control"
                          type="text"
                          v-model="employee.meal_allowance"
                        />
                      </div>
                      <div class="form_group d-flex mb-10">
                        <label>Mức lương đóng BHXH:</label>
                        <input
                          class="form-control"
                          type="text"
                          v-model="employee.insurance_salary"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="col-6 pl-30">
                    <div class="id-card_right">
                      <div class="form_group d-flex mb-10">
                        <label>Lương trách nhiệm:</label>
                        <input
                          class="form-control"
                          type="text"
                          v-model="employee.responsibility_salary"
                        />
                      </div>
                      <div class="form_group d-flex mb-10">
                        <label>Phụ cấp xăng xe:</label>
                        <input
                          class="form-control"
                          type="text"
                          v-model="employee.travel_allowance"
                        />
                      </div>
                      <div class="form_group d-flex mb-10">
                        <label>Mức đóng BHXH:</label>
                        <input
                          class="form-control"
                          type="text"
                          v-model="employee.insurance_amount"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </q-tab-panel>
          </q-tab-panels>
          <q-card-actions align="right" style="padding: 20px 0px">
            <q-btn
              color="grey-5"
              unelevated
              label="Hủy bỏ"
              v-close-popup
            />
            <q-btn v-if="prop"
              style="background-color: #236674"
              textColor="white"
              unelevated
              @click="updateEmployee"
              label="Cập nhật"
              :loading="isLoading"
            />
            <q-btn v-else
                   style="background-color: #236674"
                   textColor="white"
                   unelevated
                   @click="createEmployee"
                   label="Lưu lại"
                   :loading="isLoading"
            />
          </q-card-actions>
        </div>
      </q-card-section>
    </q-card>
  </q-dialog>
</template>

<script>
export default {
  name: 'CreateEmployee',
}
</script>

<script setup>
import {ref, defineProps, computed, watch} from "vue";
import {useRouter} from "vue-router";
import {useEmployee} from "src/modules/hrm/composables/useEmployee";
const { prop } = defineProps(['prop']);
const { employee, isLoading } = useEmployee();
const router = useRouter();
const tab = ref("mails");
const emit = defineEmits(['create-employee', 'update-employee'])
const files = ref(null);

const createEmployee = () => {
  emit('create-employee', employee.value)
}
const updateEmployee = () => {
  emit('update-employee', employee.value);
};

const previewImage = computed(() => {
  if (files.value instanceof File) {
    return URL.createObjectURL(files.value);
  } else if (employee.value.avatar) {
    return employee.value.avatar;
  } else {
     return '';
  }
});

watch(files, async (newFile, oldFile) => {
  if (newFile !== oldFile && newFile instanceof File) {
    const fileReader = new FileReader();
    fileReader.readAsDataURL(newFile);
    fileReader.onload = function (evt) {
      employee.value.avatar = evt.target.result;
    }
  }
})
</script>
<style lang="scss" scoped>
@import "./assest/popup/EmployeeCreate.scss";
</style>
