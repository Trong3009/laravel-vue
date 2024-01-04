<template>
  <div class="block-info">
    <div class="q-container">
      <!-- Start: action list -->
      <div class="row q-mt-lg justify-between" style="position: relative">
        <div class="block-title">Danh sách bảng lương</div>
        <div class="btns-act q-gutter-x-md">
          <q-btn
            @click="handleOpenPopupDeleteItems"
            style="background: #ff0000; color: #ffffff"
            label="Xóa"
            no-caps
          />
          <q-btn
            @click="handleOpenPopupUpload"
            style="background: #236674; color: #ffffff"
            label="Tải lên"
            no-caps
          />
          <q-btn
            @click="handleOpenPopupCreate"
            style="background: #236674; color: #ffffff"
            label="Thêm mới"
            no-caps
          />
          <q-icon
            class="list-vertical"
            name="fa-solid fa-ellipsis-vertical"
            @click="isDropdownVisible = !isDropdownVisible"
          />
          <div
            v-if="isDropdownVisible"
            class="dropdown-menu"
            style="background-color: #ffffff; border-radius: 10px"
          >
            <q-item @click="handleDropdownItem1">
              <q-item-section class="dropdown-action">Tải xuống</q-item-section>
            </q-item>
            <q-item>
              <q-item-section
                @click="handleDropdownEdit"
                class="dropdown-action"
                >Tùy chỉnh cột</q-item-section
              >
            </q-item>
          </div>
          <div class="dropdown-edit" v-if="isDropdownEdit">
            <h3>Tùy chỉnh các cột trong bảng</h3>
            <div class="edit-infor">
              <div class="edit-personal_infor">
                <p>Thông tin cá nhân</p>
                <div
                  class="checkbox-personal"
                  v-for="(personalInfor, index) in personalInfors"
                  :key="index"
                >
                  <input type="checkbox" id="checkvalue" />
                  <label for="checkvalue">{{ personalInfor }}</label>
                </div>
              </div>
              <div class="edit-working _infor">
                <p>Thông tin cá nhân</p>
                <div
                  class="checkbox-personal"
                  v-for="(worklInfor, index) in worklInfors"
                  :key="index"
                >
                  <input type="checkbox" id="checkvalue" />
                  <label for="checkvalue">{{ worklInfor }}</label>
                </div>
              </div>
            </div>
            <q-card-actions align="right">
              <q-btn
                style="background: #d9d9d9; color: #000000; font-size: 12px"
                unelevated
                label="Hủy bỏ"
                v-close-popup
              />
              <q-btn
                style="background-color: #236674; font-size: 12px"
                color="text-white"
                unelevated
                label="Lưu lại"
              />
            </q-card-actions>
          </div>
        </div>
      </div>
      <div class="row q-mt-lg justify-between wrap">
        <div class="row q-gutter-md">
          <q-select
            style="min-width: 100px"
            standout="bg-teal text-white"
            dense
            v-model="model"
            :options="options"
            label="Đơn vị"
          />
          <q-select
            style="min-width: 85px"
            standout="bg-teal text-white"
            dense
            v-model="model"
            :options="options"
            label="Vị trí"
          />
          <q-select
            style="min-width: 115px"
            standout="bg-teal text-white"
            dense
            v-model="model"
            :options="options"
            label="Trạng thái"
          />
        </div>
        <div style="min-width: 320px">
          <q-input v-model="search" dense outlined placeholder="Tìm kiếm">
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </div>
      </div>
      <!-- End: action list -->

      <!-- Start: table -->
      <q-table
        class="q-mt-lg"
        flat
        :rows="rows"
        :columns="columns"
        virtual-scroll
        selection="multiple"
        :separator="'cell'"
        v-model:selected="selected"
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
                    <li @click="editInfo(props.rowIndex)">
                      <q-icon name="fa-solid fa-pen" />
                    </li>
                    <li @click="openPopupLockInfo(props.row)">
                      <q-icon name="fa-solid fa-lock" />
                    </li>
                    <li @click="handleOpenPopupDeleteItem()">
                      <q-icon name="fa-solid fa-trash-can" />
                    </li>
                    <li @click="handleOpenPopupInforItem(props.row.id)">
                      <q-icon name="fa-solid fa-circle-info" />
                    </li>
                  </ul>
                </q-td>
              </template>
              <template v-else-if="col.name === 'anh'">
                <q-td
                  style="vertical-align: middle"
                  :key="col.name"
                  :props="props"
                >
                  <img
                    v-if="props.row.anh"
                    style="border-radius: 50%"
                    width="40"
                    height="40"
                    :src="props.row.anh"
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
            <div class="title-del">Xóa thành phần lương</div>
            <q-space />
          </q-card-section>

          <q-card-section style="text-align: center"
            >Bạn muốn xóa thành phần lương đã chọn ?</q-card-section
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
            >Xóa các thành phần lương đã chọn ?</q-card-section
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

      <!-- Start: popup upload file -->
      <q-dialog v-model="isPopupUpload" persistent>
        <q-card style="width: 450px; height: auto; position: relative">
          <q-btn
            style="position: absolute; top: 0; right: 0; z-index: 4"
            icon="close"
            flat
            square
            dense
            v-close-popup
          />
          <q-card-section class="row items-center" style="display: block">
            <div class="title-upload">Tải lên danh sách</div>
            <div class="file-list q-my-lg">
              <p>File mẫu</p>
              <a href="">{{
                dropzoneFile ? dropzoneFile.name : "danhsachxe.xlsx"
              }}</a>
            </div>
            <drop-zone-upload-info @on-dropzone-file="handleDropZoneFile" />
          </q-card-section>
          <q-card-actions align="center" style="padding: 20px 36px">
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
              label="Lưu lại"
            />
          </q-card-actions>
        </q-card>
      </q-dialog>
      <!-- End: popup upload file -->
    </div>
  </div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
  name: "EmployeeList",
});
</script>

<script setup>
import { ref } from "vue";
import { useRoute } from "vue-router";
import DropZoneUploadInfo from "../components/dropzone/DropZoneUploadInfo.vue";

const columns = [
  {
    name: "tenBangLuong",
    required: false,
    label: "Tên Bảng Lương",
    align: "left",
    field: (row) => row.name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "donViApDung",
    align: "left",
    label: "Đơn Vị Áp Dụng",
    field: "donViApDung",
    sortable: true,
  },
  {
    name: "kyLuong",
    align: "left",
    label: "Kỳ Lương",
    field: "kyLuong",
    sortable: true,
  },
  {
    name: "viTriApDung",
    align: "left",
    label: "Vị Trí Áp Dụng",
    field: "viTriApDung",
    sortable: true,
  },
  {
    name: "trangThai",
    label: "Trạng Thái",
    align: "left",
    field: "trangThai",
    sortable: true,
  },
  {
    name: "act",
    label: "Hành động",
    align: "left",
    field: "act",
  },
];

const rows = ref([
      {
        name: "Bảng lương tháng 6/2023",
        donViApDung: "Bệnh viện mắt Hà Nội 2",
        kyLuong: "Thang 6/2023",
        viTriApDung: "Tất cả",
        trangThai: "Đang áp dụng",
        act: "",
      },
      {
        name: "Bảng lương tháng 5/2023",
        donViApDung: "Bệnh viện mắt Hà Nội 2",
        kyLuong: "Tháng 5/2023",
        viTriApDung: "Tất cả",
        trangThai: "Đang áp dụng",
        act: "",
      },
    ]);

const personalInfors = ref([
  "Mã NV",
  "Họ và tên",
  "Ngày sinh",
  "Giới tính",
  "Số điện thoại",
  "Email",
  "Địa chỉ",
  "Nguyên quán",
  "Số CMND/CCCD",
  "Ngày cấp CMND/CCCD",
  "Nơi cấp CMND/CCCD",
  "Quốc tịch",
  "Dân tộc",
  "Tôn giáo",
  "Tình trạng hôn nhân",
  "Là Đảng viên",
  "Ảnh",
  "Số tài khoản",
  "Ngân hàng",
]);

const worklInfors = ref([
  "Phòng ban",
  "Chức vụ",
  "Chức danh",
  "Mã NV",
  "Quản lý",
  "Phân loại",
  "Trạng thái",
  "Ngày bắt đầu làm việc",
  "Ngày kết thúc làm việc",
  "Nơi công tác trước",
  "Mã HĐ",
  "Loại HĐ",
  "Thời hạn HĐ",
  "Ngày bắt đầu HĐ",
  "Ngày kết thúc HĐ",
  "Mức lương",
  "Mức BHXH",
]);

const dropzoneFile = ref("");
const isPopupDeleteItem = ref(false);
const isPopupDeleteItems = ref(false);
const isPopupUpload = ref(false);
const route = useRoute();
const isDropdownVisible = ref(false);
const isDropdownEdit = ref(false);

const handleOpenPopupDeleteItem = () => {
  isPopupDeleteItem.value = true;
};

const handleOpenPopupDeleteItems = () => {
  isPopupDeleteItems.value = true;
};

const handleOpenPopupUpload = () => {
  isPopupUpload.value = true;
};

const handleOpenPopupCreate = () => {
  isPopupCreate.value = true;
};

const handleDropZoneFile = (e) => {
  dropzoneFile.value = e;
};

const handleOpenPopupInforItem = (id) => {
  isPopupDetails.value = true;
  employeeInfo.value = rows.value.find((it) => it.id === id);
};

const handleDropdownEdit = () => {
  isDropdownEdit.value = true;
  document.body.addEventListener("click", closePopupOutside);
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
</script>

<style lang="scss" scoped>
@import "assets/scss/common.scss";
@import "assets/SalarySheetTemplate.scss";
</style>
