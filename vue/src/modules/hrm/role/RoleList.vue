<template>
  <div class="block-info">
    <div class="q-container">
      <!-- Start: action list -->
      <div class="row q-mt-lg justify-between" style="position: relative">
        <div class="block-title">Danh sách vai trò</div>
        <div class="btns-act q-gutter-x-md">
        </div>
      </div>
      <div class="row q-mt-lg justify-between wrap">
        <div class="row q-gutter-md">
          <q-btn
            :to="{ name: 'CreateRole', params: {} }"
            style="background: #236674; color: #ffffff"
            label="Thêm mới"
            no-caps
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

      <!-- Start: table danh sach vai tro-->
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
                    <li>
                      <router-link :to="{ name: 'CreateRole', params: {'roleId': props.row.id } }">
                        <q-icon style="color: black" name="fa-solid fa-pen"/>
                      </router-link>
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
            <div class="title-del">Xóa vai trò</div>
            <q-space />
          </q-card-section>

          <q-card-section style="text-align: center"
            >Bạn muốn xóa vai trò đã chọn ?</q-card-section
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
              @click="handleDeleteItem"
            />
          </q-card-actions>
        </q-card>
      </q-dialog>
      <!-- End: popup delete 1 item -->

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
            />
          </q-card-actions>
        </q-card>
      </q-dialog>
      <!-- End: popup delete items -->

      <!-- Start: popup details role -->
      <q-dialog v-model="isPopupDetails" persistent>
        <q-card style="width: 1110px; height: 1160px; max-width: 80vw">
          <q-tabs
            v-model="tabsDetails"
            dense
            class="info-tabs text-grey-10"
            active-color="cyan-9"
            indicator-color="cyan-9"
            align="justify"
          >
            <q-tab name="infoRole" label="Thông tin vai trò" style="padding-top: 30px"></q-tab>
<!--            <q-tab name="historyRole" label="Lịch sử" style="padding-top: 30px"></q-tab>-->
          </q-tabs>
          <q-card-section class="q-pb-none" style="padding: 0">
            <div class="employee-profile__container q-container">
              <q-tab-panels
                v-model="tabsDetails"
                animated
                class="rounded-borders"
              >
                <q-tab-panel name="infoRole">
                  <div class="row">
                    <div class="mb-10">
                        <div class="form_top">
                          <div class="image-upload">
                            <img class="preview-image" src="" alt="" style="height: 100% !important;">
                          </div>
                        </div>
                        <div>
                          <div class="d-flex mb-10">
                            <label class="q-mr-sm">Tên vai trò:</label>
                            <span>{{ role.name }}</span>
                          </div>
                          <div class="d-flex mb-10">
                            <label class="q-mr-sm">Mô tả vai trò:</label>
                            <span>{{ role.description }}</span>
                          </div>
                          <div>
                            <div class="role-checkbox">
                              <p style="color: #000000; font-weight: 600;">Chức năng</p>
                            </div>
                            <div class="role-checkbox permission-group" v-for="(permission, key) in permissions">
                              <p>{{ key }}</p>
                              <div v-if="roleId" class="d-flex align-item-center justify-center" v-for="item in permission" :key="item.permission_id">
                                  <input class="account permission-id"
                                         type="checkbox"
                                         :checked="isPermissionChecked(item.permission_id)"
                                         :value="item.permission_id"
                                         disableb
                                  />
                                  <label class="q-pl-xs">
                                    {{ item.slug }}
                                  </label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                  </div>
                </q-tab-panel>
                <q-tab-panel name="historyRole">

                </q-tab-panel>
              </q-tab-panels>
              <q-card-actions align="right" style="padding: 20px 0px">
                <q-btn
                  color="grey"
                  unelevated
                  label="Close"
                  v-close-popup
                />
              </q-card-actions>
            </div>
          </q-card-section>
        </q-card>
      </q-dialog>
    </div>
  </div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
  name: "RoleList",
});
</script>

<script setup>
import { onMounted, ref } from "vue";
import {useRole} from "src/modules/hrm/composables/useRole";
import {api} from "boot/axios";
import {BASE_URL_API, config} from "src/modules/hrm/constants";

const rowsPerPageOptions = ref([10, 20, 50, 100]);
const columns = [
  {
    name: "id",
    label: "ID",
    align: "left",
    field: "id",
    sortable: true,
  },
  {
    name: "name",
    align: "left ",
    label: "Tên vai trò",
    field: "name",
    sortable: true,
  },
  {
    name: "description",
    align: "left",
    label: "Mô tả vai trò",
    field: "description",
    sortable: true,
  },
  {
    name: "act",
    label: "Hành động",
    align: "center",
    field: "act",
  },
];

const isPopupDeleteItem = ref(false);
const isPopupDeleteItems = ref(false);
const isPopupCreate = ref(false);
const isPopupDetails = ref(false);
const tabsDetails = ref("infoRole");
const isDropdownEdit = ref(false);
const roleId = ref(null);
const permissions = ref([])
const permissionByRole = ref([]);

const {
  rows,
  filter,
  selected,
  isLoading,
  pagination,
  getListData,
  deleteDataItem,
  handleDeleteKeyWord,
  handleFilterByPosition,
  role,
  onRequest
} = useRole();

const handleOpenPopupDeleteItem = (id) => {
  isPopupDeleteItem.value = true;
  roleId.value = id;
};

const handleDeleteItem = () => {
  deleteDataItem(roleId.value)
  rows.value = rows.value.filter(
    (item) => item.id !== roleId.value
  );
  roleId.value = null;
  isPopupDeleteItem.value = false;
};

const handleOpenPopupInfoItem = (id) => {
  isPopupDetails.value = true;
  roleId.value = id;
  getRoleById(id);
};

const closePopup = () => {
  isDropdownEdit.value = false;
  document.body.removeEventListener("click", closePopupOutside);
};

const closePopupOutside = (event) => {
  if (!$el.contains(event.target)) {
    closePopup();
  }
};

const getRoleById = async (id) => {
  const response = await api.get(`${BASE_URL_API}/roles/${id}`, {...config});
  if (response.data.code === 200) {
    role.value.name = response.data.data.role.name;
    role.value.description = response.data.data.role.description;
    permissionByRole.value = response.data.data.role.permissions;
    permissions.value = response.data.data.permissions
  }
}

const isPermissionChecked = (val) => {
  let permissionIds = [];
  for (const item of Object.values(permissionByRole.value)) {
    if (item && item.permission_id != null) {
      permissionIds.push(item.permission_id);
    }
  }

  return permissionIds.includes(val);
};


onMounted(() => {
  getListData();
})
</script>

<style lang="scss" scoped>
@import "assets/scss/common.scss";
@import "./assest/popup/RoleDetail.scss";
@import "./assest/popup/CreateRole.scss";
</style>
