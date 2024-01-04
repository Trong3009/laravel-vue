<template>
  <q-dialog persistent>
    <q-card style="width: 1110px; max-width: 80vw">
      <q-card-section class="q-pb-none">
        <div class="q-container">
          <div class="form-group q-mb-md-lg pt-20">
            <h3 v-if="isEdit" class="add__title">Cập nhật thông tin tài khoản</h3>
            <h3 v-else class="add__title">Thêm mới tài khoản</h3>
          </div>
          <q-form ref="myForm" @submit.prevent="handleSubmit(isEdit)" @reset="resetForm" class="q-gutter-md">
            <div class="form_box mb-10">
              <h3 class="q-mb-md">Thông tin nhân sự</h3>
              <div class="row justify-between">
                <div class="col-6">
                  <div class="form_group d-flex mb-10">
                    <label class="label_custom">Họ và tên:</label>
                    <q-input
                      v-if="isEdit" class="input_custom"
                      outlined
                      :dense="true"
                      v-model="employeeInfo.name"
                      disable
                    />
                    <q-select
                      v-else
                      class="select_custom" v-model="account.user_detail_id"
                      :dense="true"
                      outlined
                      emit-value
                      map-options
                      lazy-rules
                      @update:model-value="(val) => filterEmployeeInfo(val)"
                      :options="optionEmployees"
                      :rules="requireRules"
                    />
                  </div>
                  <div class="form_group d-flex mb-10">
                    <label class="label_custom">Số điện thoại:</label>
                    <q-input
                      class="input_custom"
                      outlined
                      :dense="true"
                      v-model="employeeInfo.phone"
                      disable
                    />
                  </div>
                  <div class="form_group d-flex mb-10">
                    <label class="label_custom">Phòng ban:</label>
                    <q-input
                      class="input_custom" outlined :dense="true"
                      v-model="employeeInfo.department_id"
                      disable
                    />
                  </div>
                </div>

                <div class="col-6">
                  <div class="form_group d-flex mb-10">
                    <label class="label_custom">Mã nhân viên:</label>
                    <q-input
                      class="input_custom" outlined :dense="true"
                      v-model="employeeInfo.code"
                      disable
                    />
                  </div>
                  <div class="form_group d-flex mb-10">
                    <label class="label_custom">Email:</label>
                    <q-input
                      class="input_custom" outlined :dense="true"
                      v-model="employeeInfo.email"
                      disable
                    />
                  </div>
                  <div class="form_group d-flex mb-10">
                    <label class="label_custom">Trạng thái:</label>
                    <q-input
                      class="input_custom" outlined :dense="true"
                      :model-value="displayWorkStatus(employeeInfo.work_status)"
                      disable
                    />
                  </div>
                </div>
              </div>
            </div>
            <div class="form_box mb-10">
              <div class="form_group d-flex mb-10">
                <label class="label_custom">Người quản lý:</label>
                <q-select
                  class="select_custom"
                  :dense="true"
                  outlined
                  v-model="account.parent_id"
                  emit-value
                  map-options
                  :options="optionUsers"
                />
              </div>
            </div>

            <div class="form_box mb-10">
              <h3 class="q-mb-md">Thông tin đăng nhập</h3>
              <div class="row">
                <div class="col-6">
                  <div class="form_group d-flex mb-10">
                    <label class="label_custom">Tên tài khoản:</label>
                    <q-input
                      class="input_custom"
                      outlined
                      :dense="true"
                      lazy-rules
                      :rules="requireRules"
                      v-model="account.user_name"
                    />
                  </div>

                  <div class="form_group d-flex mb-10">
                    <label class="label_custom">Mật khẩu:</label>
                    <q-input
                      v-if="!isEdit"
                      class="input_custom"
                      outlined
                      :dense="true"
                      type="password"
                      lazy-rules
                      :rules="passwordRules"
                      v-model="account.password"
                    />
                    <q-input
                      v-else
                      class="input_custom"
                      outlined
                      :dense="true"
                      type="password"
                      v-model="account.password"
                    />
                  </div>
                </div>
                <div class="col-6">
                  <div class="form_group d-flex mb-10">
                    <label class="label_custom">Vai trò:</label>
                    <q-select
                      class="select_custom" v-model="account.role_id"
                      outlined
                      :dense="true"
                      emit-value
                      map-options
                      :options="optionRoles"
                    />
                  </div>
                  <div class="form_group d-flex mb-10" style="padding-top: 20px;">
                    <label class="label_custom no-wrap"> Xác nhận mật khẩu:</label>
                    <q-input
                      v-if="!isEdit"
                      class="input_custom"
                      outlined
                      :dense="true"
                      type="password"
                      v-model="password_confirmation"
                      lazy-rules
                      :rules="passwordRules"
                    />
                    <q-input
                      v-else
                      class="input_custom"
                      outlined
                      :dense="true"
                      type="password"
                      v-model="password_confirmation"
                    />
                  </div>
                </div>
              </div>
            </div>
            <q-card-actions align="right" style="padding: 20px 0px">
              <q-btn v-if="!isEdit" label="Reset" type="reset" color="primary" class="q-ml-sm" />
              <q-btn
                style="background: #d9d9d9"
                unelevated
                label="Hủy bỏ"
                v-close-popup
              />
              <q-btn
                v-if="isEdit"
                style="background-color: #236674"
                color="text-white"
                unelevated
                type="submit"
                label="Cập nhật"
              />
              <q-btn
                v-else
                type="submit"
                style="background-color: #236674"
                color="text-white"
                unelevated
                label="Lưu lại"
              />
            </q-card-actions>
          </q-form>
        </div>
      </q-card-section>
    </q-card>
  </q-dialog>
</template>

<script>
export default {
  name: 'CreateAccount',
}
</script>

<script setup>
import {ref, computed, onMounted} from "vue";
import {useRouter} from "vue-router";
import {useValidate} from "src/modules/hrm/composables/useValidate";
import {useAccount} from "src/modules/hrm/composables/useAccount";
import {api} from "boot/axios";
import {config, BASE_URL_API } from "src/modules/hrm/constants";

const apiEmployee = `${BASE_URL_API}/userDetails`;
const {filter, account, apiAccount} = useAccount();
const router = useRouter();
const password_confirmation = ref(null);

const {isEdit, accountId, employeeInfo, optionRoles} = defineProps(['isEdit', 'accountId', 'employeeInfo', 'optionRoles'])
const optionUsers = ref([]);
const optionEmployees = ref([]);
const employees = ref(null);
const myForm = ref(null)

const {
  passwordRules,
  requireRules
} = useValidate();
const emit = defineEmits(['create-account', 'update-account', 'employee-info'])

const handleSubmit = (isEdit) => {
  if (isEdit) {
    updateAccount();
  } else {
    createAccount();
  }
};
const createAccount = () => {
  validate().then((isValid) => {
    if (isValid) {
      emit('create-account', account.value)
    } else {
      alert('Form is not valid. Please check the fields.');
      return Promise.reject('Validation failed');
    }
  }).catch((error) => {
    console.error(error);
  });
};
const updateAccount = () => {
  validate().then((isValid) => {
    if (isValid) {
      emit('update-account', account.value);
    } else {
      alert('Form is not valid. Please check the fields.');
      return Promise.reject('Validation failed');
    }
  }).catch((error) => {
    console.error(error);
  });
};

const getAllUsers = async () => {
  const response = await api.get(`${apiAccount}/all-user`, {
    ...config,
  });
  if (response.data.code === 200 ) {
    optionUsers.value = [
      {label: 'Chọn người quản lý', value: ''},
      ...response.data.data.map(item => ({label: item.name, value: item.id}))
    ];
  }
}

const getAllEmployee = async () => {
  const response = await api.get(`${apiEmployee}/all-user-detail`, {
    ...config,
  });
  if (response.data.code === 200) {
    employees.value = response.data.data;
    optionEmployees.value = [
      {label: 'Chọn hồ sơ', value: ''},
      ...employees.value.map(item => ({label: item.name, value: item.id}))
    ];
  }
}
const displayWorkStatus = (val) => {
  if (val === 1) {
    return 'Đang làm việc';
  }
  if (val === 2) {
    return 'Đã nghỉ việc';
  }
  if (val === 3) {
    return 'Nghỉ việc tạm thời';
  }
}
const filterEmployeeInfo = (val) => {
  const idFilter = Number(val);
  const filteredEmployeeInfoArray = employees.value.filter(item => item.id === idFilter);
  const employeeInfo = filteredEmployeeInfoArray.length > 0 ? filteredEmployeeInfoArray[0] : {};
  emit('employee-info', employeeInfo)
}

// Validate form
const validate = async () => {
  const isValid = await myForm.value.validate();
  if (!isValid) {
    alert('Validation failed. Please check the fields.');
  }
  return isValid;
}
const resetForm = () => {
  account.value.user_detail_id = "";
  account.value.user_name = "";
  account.value.password = "";
  password_confirmation.value = "";
};

onMounted(() => {
  getAllUsers();
  getAllEmployee();
})
</script>
<style lang="scss" scoped>
@import "./assest/popup/AccountCreate.scss";
</style>
