<template>
  <div class="block-info">
    <div class="q-container">
      <!-- Start: action list -->
      <div class="row q-mt-lg justify-between" style="position: relative">
        <div class="block-title">Danh sách tài khoản</div>
        <div class="btns-act q-gutter-x-md">
          <q-btn
            @click="handleOpenPopupDeleteItems"
            style="background: #ff0000; color: #ffffff"
            label="Xóa"
            no-caps
          />
          <q-btn
            @click="handleOpenPopupCreate"
            style="background: #236674; color: #ffffff"
            label="Thêm mới"
            no-caps
          />
        </div>
      </div>
      <div class="row q-mt-lg justify-between wrap">
        <div class="row q-gutter-md">
          <q-select
            style="min-width: 136px"
            standout="bg-teal text-white"
            dense
            label="Vai trò"
            emit-value
            map-options
            v-model="filter.role_id"
            :options="optionRoles"
            @update:model-value="(val) => handleFilterByPosition(val)"
          />
        </div>
        <div style="min-width: 320px">
          <q-input v-model="filter.keyword" dense outlined placeholder="Tìm kiếm" @keyup.enter="getListData">
            <template v-slot:append>
              <q-icon
                v-if="filter.keyword !== ''"
                name="close"
                @click="handleDeleteKeyWord"
                class="cursor-pointer"
              />
              <q-icon
                name="search"
                @click="getListData"
              />
            </template>
          </q-input>
        </div>
      </div>
      <!-- End: action list -->

      <!-- Start: table danh sach tai khoan-->
      <q-table
        class="q-mt-lg"
        flat
        :rows="rows"
        :columns="columns"
        :loading="isLoading"
        selection="multiple"
        :separator="'cell'"
        v-model:selected="selected"
        v-model:pagination="pagination"
        :rows-per-page-options="rowsPerPageOptions"
        rows-per-page-label="Hiển thị"
        :binary-state-sort="true"
        @request="onRequest"
      >
        <template v-slot:body="props">
          <q-tr :props="props">
            <q-td>
              <q-checkbox v-model="props.selected" />
            </q-td>
            <template v-for="col in props.cols">
              <template v-if="col.name === 'act'">
                <q-td :key="col.name" :props="props">
                  <ul class="btns-icon">

                    <li @click="handleOpenPopupLock(props.row.is_active, props.row.id)">
                      <q-icon v-if="props.row.is_active === 1" name="fa-solid fa-unlock" color="blue"/>
                      <q-icon v-else name="fa-solid fa-lock" color="red" />
                    </li>
                    <li @click="handleOpenPopupEditItem(props.row.id)">
                      <q-icon name="fa-solid fa-pen" />
                    </li>
                    <li @click="handleOpenPopupDeleteItem(props.row.id)">
                      <q-icon name="fa-solid fa-trash-can" />
                    </li>
                    <li @click="handleOpenPopupInfoItem(props.row.id)">
                      <q-icon name="fa-solid fa-circle-info" />
                    </li>
                  </ul>
                </q-td>
              </template>
              <template v-else-if="col.name === 'avatar'">
                <q-td
                  style="vertical-align: middle"
                  :key="col.name"
                  :props="props"
                >
                  <img
                    v-if="props.row.avatar"
                    style="border-radius: 50%"
                    width="40"
                    height="40"
                    :src="props.row.avatar"
                    alt=""
                  />
                </q-td>
              </template>
              <template v-else>
                <q-td
                  style="vertical-align: middle"
                  :key="col.name"
                  :props="props"
                >
                  {{ col.value }}
                </q-td>
              </template>
            </template>
          </q-tr>
        </template>
      </q-table>
      <!-- End: table -->

      <!-- Start: popup delete 1 item -->
      <q-dialog v-model="isPopupDeleteItem" persistent>
        <q-card style="width: 420px; height: auto; position: relative">
          <q-btn
            style="position: absolute; top: 0; right: 0; z-index: 4"
            icon="close"
            flat
            square
            dense
            v-close-popup
          />
          <q-card-section class="row items-center">
            <q-space />
            <div class="title-del">Xóa tài khoản</div>
            <q-space />
          </q-card-section>

          <q-card-section style="text-align: center"
            >Bạn muốn xóa nhân sự đã chọn ?</q-card-section
          >
          <q-card-actions align="center" style="margin-top: 26px">
            <q-btn
              style="background: #d9d9d9"
              unelevated
              label="Hủy bỏ"
              v-close-popup
            />
            <q-btn
              style="background-color: #2747a0"
              color="text-white"
              unelevated
              label="Đồng ý"
              @click="handleDeleteAccount"
            />
          </q-card-actions>
        </q-card>
      </q-dialog>
      <!-- End: popup delete 1 item -->
      <!-- Start: popup lock -->
      <q-dialog v-model="isPopupLock" persistent>
        <q-card style="width: 420px; height: auto; position: relative">
          <q-btn
            style="position: absolute; top: 0; right: 0; z-index: 4"
            icon="close"
            flat
            square
            dense
            v-close-popup
          />
          <q-card-section class="row items-center">
            <q-space />
            <div v-if="isActive === 1" class="title-del">Khóa tài khoản</div>
            <div v-else class="title-del">Mở khóa tài khoản</div>
            <q-space />
          </q-card-section>

          <q-card-section v-if="isActive === 1" style="text-align: center">Bạn muốn khóa tài khoản này ?</q-card-section>
          <q-card-section v-else style="text-align: center">Bạn muốn mở khóa tài khoản này ?</q-card-section>
          <q-card-actions align="center" style="margin-top: 26px">
            <q-btn
              style="background: #d9d9d9"
              unelevated
              label="Hủy bỏ"
              v-close-popup
            />
            <q-btn
              style="background-color: #2747a0"
              color="text-white"
              unelevated
              label="Đồng ý"
              @click="lockAndUnlock"
            />
          </q-card-actions>
        </q-card>
      </q-dialog>
      <!-- End: popup lock -->
      <!-- show popup khi ko click chon selected -->
      <q-dialog v-model="isPopupSelected" persistent>
        <q-card style="width: 420px; height: auto; position: relative">
          <q-btn
            style="position: absolute; top: 0; right: 0; z-index: 4"
            icon="close"
            flat
            square
            dense
            v-close-popup
          />
          <q-card-section style="text-align: center">Vui lòng chọn thông tin ?</q-card-section>
          <q-card-actions align="center" style="margin-top: 26px">
            <q-btn
              style="background-color: #2747a0"
              color="text-white"
              unelevated
              label="Đồng ý"
              v-close-popup
            />
          </q-card-actions>
        </q-card>
      </q-dialog>
      <!-- Start: popup delete items -->
      <q-dialog v-model="isPopupDeleteItems" persistent>
        <q-card style="width: 420px; height: auto; position: relative">
          <q-btn
            style="position: absolute; top: 0; right: 0; z-index: 4"
            icon="close"
            flat
            square
            dense
            v-close-popup
          />
          <q-card-section class="row items-center">
            <q-space />
            <div class="title-del">Xóa</div>
            <q-space />
          </q-card-section>

          <q-card-section style="text-align: center"
            >Xóa các thông tin đã chọn ?</q-card-section
          >
          <q-card-actions align="center" style="margin-top: 26px">
            <q-btn
              style="background: #d9d9d9"
              unelevated
              label="Hủy bỏ"
              v-close-popup
            />
            <q-btn
              style="background-color: #2747a0"
              color="text-white"
              unelevated
              label="Đồng ý"
              @click="deleteAccounts"
            />
          </q-card-actions>
        </q-card>
      </q-dialog>
      <!-- End: popup delete items -->

      <create-account
        v-model="isPopupCreate"
        :isEdit="isEdit"
        :accountId="accountId"
        :employeeInfo="employeeInfo"
        :optionRoles="optionRoles"
        @create-account="createData($event)"
        @update-account="updateData($event)"
        @employee-info="editUserInfo($event)"
      />

      <!-- Start: popup detail -->
      <q-dialog v-model="isPopupDetail" persistent>
          <q-card style="width: 1110px; max-width: 80vw">
            <q-card-section class="q-pb-none">
              <div class="q-container">
                <div class="form-group q-mb-md-lg pt-20">
                  <h3 class="add__title">Thông tin tài khoản</h3>
                </div>
                <q-form class="q-gutter-md">
                  <div class="form_box mb-10">
                    <div class="row justify-between">
                      <div class="col-6">
                        <div class="form_group d-flex mb-10">
                          <label class="label_custom">Họ và tên:</label>
                          <q-input
                            class="input_custom"
                            outlined
                            :dense="true"
                            v-model="employeeInfo.name"
                            disable
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
                      <q-input v-if="account.user_parent && account.user_parent.user_details"
                        class="select_custom"
                        :dense="true"
                        outlined
                        v-model="account.user_parent.user_details.name"
                        disable
                      />
                      <q-input v-else
                        class="select_custom"
                        :dense="true"
                        outlined
                        disable
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
                            v-model="account.user_name"
                            disable
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
                            selected
                            :options="optionRoles"
                            disable
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                  <q-card-actions align="right" style="padding: 20px 0px">
                    <q-btn
                      style="background: #d9d9d9"
                      unelevated
                      label="Close"
                      v-close-popup
                    />
                  </q-card-actions>
                </q-form>
              </div>
            </q-card-section>
          </q-card>
      </q-dialog>
    </div>
  </div>
</template>

<script>
import { defineComponent, isProxy, toRaw } from "vue";

export default defineComponent({
  name: "EmployeeList",
});
</script>

<script setup>
import { onMounted, ref } from "vue";
import { useAccount } from "src/modules/hrm/composables/useAccount";
import CreateAccount from "./CreateAccount.vue";
import { date } from "quasar";
import {api} from "boot/axios";
import {BASE_URL_API} from "src/modules/hrm/constants";
const rowsPerPageOptions = ref([10, 20, 50, 100]);
const isPopupDeleteItem = ref(false);
const isPopupCreate = ref(false);
const isPopupDetail = ref(false);
const accountId = ref("");
const optionRoles = ref(null);
const isEdit = ref(false);
const isPopupLock = ref(false);
const isActive = ref("");

const employeeInfo = ref({
  id: "",
  code: "",
  name: "",
  department_id: "",
  phone: "",
  email: "",
  work_status: "",
});
const columns = [
  {
    name: "user_name",
    align: "left",
    label: "Họ và tên",
    field: "user_details",
    sortable: true,
    format: (userDetails) => {
      if (isProxy(userDetails)) {
        userDetails = toRaw(userDetails);
      }
      return userDetails?.name
    }
  },
  {
    name: "email",
    align: "left",
    label: "Email",
    field: "user_details",
    sortable: false,
    format: (userDetails) => {
      if (isProxy(userDetails)) {
        userDetails = toRaw(userDetails);
      }
      return userDetails?.email
    }
  },
  {
    name: "parent_id",
    label: "Quản lý",
    align: "left",
    field: "user_parent",
    sortable: false,
    format: (userParent) => {
      if (isProxy(userParent)) {
        userParent = toRaw(userParent);
        return userParent.user_details?.name
      }
      return null;
    }
  },
  {
    name: "role_id",
    label: "Vai trò",
    align: "left",
    field: "roles",
    sortable: false,
    format: (roles) => {
      if (isProxy(roles)) {
        roles = toRaw(roles);
        return roles[0]?.name
      }
      return null;
    }
  },
  {
    name: "work_status",
    label: "Trạng thái",
    align: "left",
    field: (row) => {
      if (isProxy(row)) {
        const account = toRaw(row);
        if (account.user_details && account.user_details.work_status) {
          if (account.user_details.work_status === 1) {
            return 'Đang làm việc';
          }
          if (account.user_details.work_status === 2) {
            return 'Đã nghỉ việc';
          }
          if (account.user_details.work_status === 3) {
            return 'Nghỉ việc tạm thời';
          }
        }
      }
      return null;
    },
    sortable: false,
  },
  {
    name: "created_at",
    align: "left",
    label: "Ngày tạo",
    field: "created_at",
    sortable: true,
    format: (val) => { return date.formatDate(val, 'DD/MM/YYYY')}
  },
  {
    name: "updated_at",
    align: "left",
    label: "Ngày cập nhật",
    field: "updated_at",
    sortable: true,
    format: (val) => { return date.formatDate(val, 'DD/MM/YYYY')}
  },
  {
    name: "act",
    label: "Hành động",
    align: "center",
    field: "act",
  },
];

const {
  rows,
  account,
  filter,
  selected,
  isLoading,
  pagination,
  getListData,
  deleteDataItem,
  updateItem,
  createAccount,
  handleDeleteKeyWord,
  handleFilterByPosition,
  isPopupSelected,
  deleteMultiItem,
  isPopupDeleteItems,
  lockAndUnlockAccount,
  onRequest
} = useAccount();

const handleOpenPopupDeleteItem = (id) => {
  isPopupDeleteItem.value = true;
  accountId.value = id;
};

const handleDeleteAccount = () => {
  deleteDataItem(accountId.value)
  rows.value = rows.value.filter(
    (item) => item.id !== accountId.value
  );
  accountId.value = null;
  isPopupDeleteItem.value = false;
};

const handleOpenPopupDeleteItems = () => {
  if (selected.value.length > 0) {
    isPopupDeleteItems.value = true;
  } else {
    isPopupSelected.value = true;
  }
};

const handleOpenPopupCreate = () => {
  isPopupCreate.value = true;
  isEdit.value = false;
};


const handleOpenPopupInfoItem = (id) => {
  accountId.value = id;
  isEdit.value = true;
  isPopupDetail.value = true;
  account.value = rows.value.find((item) => item.id === id);
  if (account.value.roles[0]) {
    account.value.role_id = account.value.roles[0].id;
  }
  employeeInfo.value = account.value.user_details;
};

const handleOpenPopupEditItem = (id) => {
  accountId.value = id;
  isEdit.value = true;
  isPopupCreate.value = true;
  account.value = rows.value.find((item) => item.id === id);
  if (account.value.roles[0]) {
    account.value.role_id = account.value.roles[0].id;
  }
  employeeInfo.value = account.value.user_details;
};

const handleOpenPopupLock = (is_active, id) => {
  isPopupLock.value = true;
  isActive.value = is_active;
  accountId.value = id;
}

const lockAndUnlock = async () => {
  let data = {
    is_active: isActive.value,
  }
  const response = await lockAndUnlockAccount(accountId.value, data);
  if (response.data.code === 200) {
    isPopupLock.value = false;
    await getListData();
  }
}

const sortTable = (columnName) => {
  if (pagination.value.sortBy === columnName) {
    if (pagination.value.descending === 'asc') {
      pagination.value.descending = 'desc';
    } else {
      pagination.value.descending = 'asc';
    }
  } else {
    pagination.value.sortBy = columnName;
    if (pagination.value.descending === 'asc') {
      pagination.value.descending = 'desc';
    } else {
      pagination.value.descending = 'asc';
    }
  }
};

const createData = async (e) => {
  await createAccount(e)
  isPopupCreate.value = false;
}

const updateData = async (e) => {
  await updateItem(accountId.value, e)
  isPopupCreate.value = false;
}

const editUserInfo = (e) => {
  employeeInfo.value = e;
}

const getRoles = async () => {
  const response = await api.get(`${BASE_URL_API}/roles/all-role`);
  if (response.data.code === 200) {
    optionRoles.value = [
      {label: 'Chọn vai trò', value: ''},
      ...response.data.data.map(item => ({label: item.name, value: item.id}))
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
const deleteAccounts = () => {
  const ids = [];
  selected.value.map((item) => {
    ids.push(item.id)
  })
  deleteMultiItem(ids)
};
onMounted(() => {
  getListData();
  getRoles()
})
</script>

<style lang="scss" scoped>
@import "assets/scss/common.scss";
@import "./assest/popup/AccountCreate.scss";
</style>
