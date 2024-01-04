<template>
  <div class="block-info">
    <div class="q-container list-form">
      <!-- Start: action list -->
      <div class="row q-mt-lg" style="padding-top: 32px">
        <div class="block-title">Danh sách Phiếu làm ngoài giờ</div>
      </div>
      <div class="row q-mt-lg justify-between wrap">
        <div class="row q-gutter-md">
          <q-select
            style="min-width: 115px"
            standout="bg-teal text-white"
            dense
            v-model="filter"
            :options="[
              { label: 'Đã duyệt', value: 1 },
              { label: 'Đang chờ', value: 2 },
              { label: 'Đã hủy', value: 3 },
            ]"
            label="Trạng thái"
            @update:model-value="(val) => handleFilterByStatus(val)"
          />
        </div>
        <div class="btns-act q-gutter-x-md">
          <q-btn
            @click="handleOpenPopupCreate"
            style="background: #236674; color: #ffffff"
            label="Thêm mới"
            no-caps
          />
        </div>
      </div>
      <!-- End: action list -->

      <!-- Start: table -->
      <q-table
        class="q-mt-lg no-border-radius"
        :rows="rows"
        :columns="columns"
        virtual-scroll
        selection="multiple"
        :separator="'cell'"
        v-model:selected="selected"
        rows-per-page-label="Hiển thị"
        loading-label="Đang tải..."
        :rows-per-page-options="rowsPerPageOptions"
        :binary-state-sort="true"
        hide-bottom=""
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
                    <li
                      @click="editInfo(props.row.id)"

                    >
                      <q-icon name="fa-solid fa-pen" />
                    </li>
                    <li
                      @click="handleReportOverTime(props.row.id)"
                      v-if="props.row.active === 1"
                    >
                      <q-icon name="fa-solid fa-flag" />
                    </li>
                    <li @click="handleOpenPopupInforItem(props.row.id)">
                      <q-icon name="fa-solid fa-circle-info" />
                    </li>
                    <li
                    v-if="props.row.status === 2"
                    @click="handleOpenPopupDeleteItem(props.row.id)">
                      <q-icon name="fa-solid fa-trash-can" />
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
      <q-dialog v-model="isPopupCreate" persistent>
        <q-card style="width: 900px; max-width: 80vw">
          <q-card-section class="q-pb-none">
            <q-form class="q-container id-card">
              <div class="form-group mb-10 form-title__overtime">
                <h3 class="text-grey-10 add__title">
                  {{ isEdit ? 'Thêm mới phiếu làm thêm' : 'Sửa phiếu làm thêm' }}
                </h3>
                <q-btn icon="close" flat square dense v-close-popup />
              </div>
              <div class="form-group mb-10 overtime-date">
                <q-input
                  v-model="otvote.date_ot"
                  outlined
                  type="date"
                  :dense="true"
                  class="input-field_info otdate"
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for="">Ngày</label>
                  </template>
                </q-input>
                <q-select
                  outlined
                  v-model="otvote.date_type"
                  emit-value
                  map-options
                  :options="[
                    { label: 'Ngày thường', value: 'weekdays' },
                    { label: 'Ngày Lễ', value: 'holiday' },
                    { label: 'Ngày nghỉ', value: 'offday' },
                  ]"
                  @update:model-value="(val) => updateOptiondate_type(val)"
                  :dense="true"
                  class="input-field_info"
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for="">Phân loại</label>
                  </template>
                </q-select>
                <q-select
                  outlined
                  v-model="otvote.shift"
                  emit-value
                  map-options
                  :options="[
                    { label: 'Ngày', value: 'day' },
                    { label: 'Đêm', value: 'night' },
                  ]"
                  @update:model-value="(val) => updateOptionShift(val)"
                  @blur="calculateExtraSalary"
                  :dense="true"
                  class="input-field_info"
                >
                  <template v-slot:before>
                    <label
                      class="input-field_label"
                      style="font-size: 14px"
                      for=""
                      >Ca</label
                    >
                  </template>
                </q-select>
              </div>
              <div class="form-group mb-10 overtime-time">
                <q-input
                  outlined
                  type="time"
                  :dense="true"
                  class="input-field_info"
                  v-model="otvote.start_time"
                  @blur="(e) => calculateTime()"
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for="">Từ</label>
                  </template>
                </q-input>
                <q-input
                  outlined
                  type="time"
                  :dense="true"
                  class="input-field_info"
                  v-model="otvote.end_time"
                  @blur="(e) => calculateTime()"
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for="">Đến</label>
                  </template>
                </q-input>
                <q-input
                  outlined
                  type="text"
                  :dense="true"
                  class="input-field_info"
                  v-model="otvote.total_time"
                  disable
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for="">Số giờ</label>
                  </template>
                </q-input>
                <q-input
                  outlined
                  v-model="otvote.multi_time"
                  type="text"
                  :dense="true"
                  class="input-field_info"
                  disable
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for="">Hệ số</label>
                  </template>
                </q-input>
                <q-input
                  outlined
                  type="text"
                  :dense="true"
                  class="input-field_info"
                  v-model="otvote.total_multi"
                  disable
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for=""
                      >Số giờ nhân hệ số</label
                    >
                  </template>
                </q-input>
              </div>
              <div class="form-group mb-10 ot-icon">
                <q-file
                    v-model="otvote.file_overtime"
                      label="Thêm"
                      style="max-width: 300px;height: 48px;overflow: hidden;"
                       >
                    <template v-slot:prepend>
                      <q-icon name="add" class="circle-icon" />
                    </template>
                  </q-file>
                </div>
              <div class="form-group mb-10 ">
                <q-input
                  outlined
                  type="text"
                  :dense="true"
                  class="input-field_info overtime-object"
                  style="width: 100%"
                  v-model="otvote.object"
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for="">Dự án</label>
                  </template>
                </q-input>
              </div>
              <div class="form-group mb-10 overtime-description">
                <q-input
                  outlined
                  type="text"
                  :dense="true"
                  class="input-field_info description"
                  style="width: 100%"
                  v-model="otvote.description"
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for="">Mô tả</label>
                  </template>
                </q-input>
              </div>
              <q-card-actions align="right" style="padding: 20px 0px">
                <q-btn
                  style="background: #d9d9d9"
                  unelevated
                  label="Hủy bỏ"
                  v-close-popup
                />
                <q-btn
                v-if="isEdit"
                  style="background-color: #236674"
                  textColor="white"
                  unelevated
                  label="CHuyển duyệt"
                  @click="saveOverTime"
                />
                <q-btn
                v-else
                  style="background-color: #236674"
                  textColor="white"
                  unelevated
                  label="CHuyển duyệt"
                  @click="updateOverTime"
                />
              </q-card-actions>
            </q-form>
          </q-card-section>
        </q-card>
      </q-dialog>
       <!-- báo cáo overtime -->
      <q-dialog v-model="isPopupReport" persistent>
        <q-card style="width: 900px; max-width: 80vw">
          <q-card-section class="q-pb-none">
            <q-form class="q-container id-card">
              <div class="form-group mb-10 form-title__overtime">
                <h3 class="text-grey-10 add__title">Báo cáo kết quả OT</h3>
                <q-btn icon="close" flat square dense v-close-popup />
              </div>
              <div class="form-group mb-10 overtime-date">
                <q-input
                  v-model="otvote.date_ot"
                  outlined
                  type="date"
                  :dense="true"
                  class="input-field_info otdate"
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for="">Ngày</label>
                  </template>
                </q-input>
                <q-select
                  outlined
                  v-model="otvote.date_type"
                  :options="[
                    { label: 'Ngày thường', value: 'weekdays' },
                    { label: 'Ngày Lễ', value: 'holiday' },
                    { label: 'Ngày nghỉ', value: 'offday' },
                  ]"
                  @blur="calculateExtraSalary"
                  :dense="true"
                  class="input-field_info"
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for="">Phân loại</label>
                  </template>
                </q-select>
                <q-select
                  outlined
                  v-model="otvote.shift"
                  :options="[
                    { label: 'Ngày', value: 'day' },
                    { label: 'Đêm', value: 'night' },
                  ]"
                  @blur="calculateExtraSalary"
                  :dense="true"
                  class="input-field_info"
                >
                  <template v-slot:before>
                    <label
                      class="input-field_label"
                      style="font-size: 14px"
                      for=""
                      >Ca</label
                    >
                  </template>
                </q-select>
              </div>
              <div class="form-group mb-10 overtime-time">
                <q-input
                  outlined
                  type="time"
                  :dense="true"
                  class="input-field_info"
                  v-model="otvote.start_time"
                  @blur="(e) => calculateTime()"
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for="">Từ</label>
                  </template>
                </q-input>
                <q-input
                  outlined
                  type="time"
                  :dense="true"
                  class="input-field_info"
                  v-model="otvote.end_time"
                  @blur="(e) => calculateTime()"
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for="">Đến</label>
                  </template>
                </q-input>
                <q-input
                  outlined
                  type="text"
                  :dense="true"
                  class="input-field_info"
                  v-model="otvote.total_time"
                  disable
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for="">Số giờ</label>
                  </template>
                </q-input>
                <q-input
                  outlined
                  v-model="otvote.multi_time"
                  type="text"
                  :dense="true"
                  class="input-field_info"
                  disable
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for="">Hệ số</label>
                  </template>
                </q-input>
                <q-input
                  outlined
                  type="text"
                  :dense="true"
                  class="input-field_info"
                  v-model="otvote.total_multi"
                  disable
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for=""
                      >Số giờ nhân hệ số</label
                    >
                  </template>
                </q-input>
              </div>
              <a class="form-group mb-10 ot-icon">
                <q-icon
                  outlined
                  type="text"
                  name="add"
                  :dense="true"
                  class="circle-icon"
                  @click="$refs.fileInput.click()"
                >
                </q-icon>
                <label style="color: #236674" for="">Thêm</label>
              </a>
              <div class="form-group mb-10 ot-icon">
                <input
                  type="file"
                  @change="handleFileUpload"
                  style="display: none"
                  ref="fileInput"
                />
              </div>
              <div class="form-group mb-10 ">
                <q-input
                  outlined
                  type="text"
                  v-model="otvote.object"
                  :dense="true"
                  class="input-field_info overtime-object"
                  style="width: 45%"
                  
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for="">Dự án</label>
                  </template>
                </q-input>
              </div>
              <div class="form-group mb-10 overtime-description">
                <q-input
                  outlined
                  type="text"
                  ref="nameRef"
                  id=""
                  emit-value
                  map-options
                  v-model="otvote.description"
                  :dense="true"
                  class="input-field_info description"
                  style="width: 100%"
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for="">Mô tả</label>
                  </template>
                </q-input>
              </div>
              <div class="form-group mb-10 reportovertime">
                <label for="">Báo cáo</label>
                <q-input
                  outlined
                  :dense="true"
                  v-model="otvote.report_ot"
                  type="textarea"
                  style="width: 100%"
                />
              </div>
              <div class="form-group mb-10 ot-icon">
                <label for="">File đính kèm</label>
                <input
                  type="file"
                  @change="handleFileUpload"
                  style="display: none"
                  ref="fileInput"
                />
                <q-icon
                  outlined
                  type="text"
                  name="add"
                  :dense="true"
                  class="circle-icon"
                  @click="$refs.fileInput.click()"
                ></q-icon>
              </div>
              <q-card-actions align="right" style="padding: 20px 0px">
                <q-btn
                  style="background: #d9d9d9"
                  unelevated
                  label="Hủy bỏ"
                  v-close-popup
                />
                <q-btn
                  style="background-color: #236674"
                  textColor="white"
                  unelevated
                  label="Lưu lại"
                  @click="repairOverTimeVote"
                />
              </q-card-actions>
            </q-form>
          </q-card-section>
        </q-card>
      </q-dialog>

      <q-dialog v-model="isPopupDetails" persistent>
        <q-card style="width: 900px; max-width: 80vw">
          <q-card-section class="q-pb-none">
            <q-form class="q-container id-card">
              <div class="form-group mb-10 form-title__overtime">
                <h3 class="text-grey-10 add__title">info kết quả OT</h3>
                <q-btn icon="close" flat square dense v-close-popup />
              </div>
              <div class="form-group mb-10 overtime-date">
                <q-input
                  v-model="otvote.date_ot"
                  outlined
                  type="date"
                  :dense="true"
                  class="input-field_info otdate"
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for="">Ngày</label>
                  </template>
                </q-input>
                <q-select
                  outlined
                  v-model="otvote.date_type"
                  :options="[
                    { label: 'Ngày thường', value: 'weekdays' },
                    { label: 'Ngày Lễ', value: 'holiday' },
                    { label: 'Ngày nghỉ', value: 'offday' },
                  ]"
                  @blur="calculateExtraSalary"
                  :dense="true"
                  class="input-field_info"
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for="">Phân loại</label>
                  </template>
                </q-select>
                <q-select
                  outlined
                  v-model="otvote.shift"
                  :options="[
                    { label: 'Ngày', value: 'day' },
                    { label: 'Đêm', value: 'night' },
                  ]"
                  @blur="calculateExtraSalary"
                  :dense="true"
                  class="input-field_info"
                >
                  <template v-slot:before>
                    <label
                      class="input-field_label"
                      style="font-size: 14px"
                      for=""
                      >Ca</label
                    >
                  </template>
                </q-select>
              </div>
              <div class="form-group mb-10 overtime-time">
                <q-input
                  outlined
                  type="time"
                  :dense="true"
                  class="input-field_info"
                  v-model="otvote.start_time"
                  @blur="(e) => calculateTime()"
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for="">Từ</label>
                  </template>
                </q-input>
                <q-input
                  outlined
                  type="time"
                  :dense="true"
                  class="input-field_info"
                  v-model="otvote.end_time"
                  @blur="(e) => calculateTime()"
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for="">Đến</label>
                  </template>
                </q-input>
                <q-input
                  outlined
                  type="text"
                  :dense="true"
                  class="input-field_info"
                  v-model="otvote.total_time"
                  disable
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for="">Số giờ</label>
                  </template>
                </q-input>
                <q-input
                  outlined
                  v-model="otvote.multi_time"
                  type="text"
                  :dense="true"
                  class="input-field_info"
                  disable
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for="">Hệ số</label>
                  </template>
                </q-input>
                <q-input
                  outlined
                  type="text"
                  :dense="true"
                  class="input-field_info"
                  v-model="otvote.total_multi"
                  disable
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for=""
                      >Số giờ nhân hệ số</label
                    >
                  </template>
                </q-input>
              </div>
              <a class="form-group mb-10 ot-icon">
                <q-icon
                  outlined
                  type="text"
                  name="add"
                  :dense="true"
                  class="circle-icon"
                >
                </q-icon>
                <label style="color: #236674" for="">Thêm</label>
              </a>
              <div class="form-group mb-10">
                <q-input
                  outlined
                  type="text"
                  :dense="true"
                  class="input-field_info overtime-object"
                  style="width: 45%"
                  v-model="otvote.object"
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for="">Dự án</label>
                  </template>
                </q-input>
              </div>
              <div class="form-group mb-10 overtime-description">
                <q-input
                  outlined
                  type="text"
                  :dense="true"
                  class="input-field_info description"
                  style="width: 100%"
                  v-model="otvote.description"
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for="">Mô tả</label>
                  </template>
                </q-input>
              </div>
              <div class="form-group mb-10 reportovertime">
                <label for="">Báo cáo</label>
                <q-input
                  outlined
                  :dense="true"
                  type="textarea"
                  style="width: 90%"
                  v-model="otvote.description"
                />
              </div>
              <div class="form-group mb-10 ot-icon">
                <label for="">File đính kèm</label>
                <input
                  type="file"
                  @change="handleFileUpload"
                  style="display: none"
                  ref="fileInput"
                />
                <q-icon
                  outlined
                  type="text"
                  name="add"
                  :dense="true"
                  class="circle-icon"
                  @click="$refs.fileInput.click()"
                ></q-icon>
              </div>
              <q-card-actions align="right" style="padding: 20px 0px">
                <q-btn
                  style="background: #d9d9d9"
                  unelevated
                  label="Hủy bỏ"
                  v-close-popup
                />
                <q-btn
                  style="background-color: #236674"
                  textColor="white"
                  unelevated
                  label="Lưu lại"
                  @click="repairOverTimeVote1"
                />
              </q-card-actions>
            </q-form>
          </q-card-section>
        </q-card>
      </q-dialog>
            <!-- Xóa phiếu -->
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
            <div class="title-del">Xóa nhân sự</div>
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
              @click="handleDeleteInfoBreak"
            />
          </q-card-actions>
        </q-card>
      </q-dialog>
    </div>
  </div>
</template>

<script>
export default {}
</script>
<script setup>
import { ref, onMounted, watch, isProxy, toRaw } from "vue";
import { useRoute } from "vue-router";
import { useOTVote } from "../composables/useOTVote";
const columns = [
  {
    name: "date_ot",
    align: "left ",
    label: "ngày",
    field: "date_ot",
    sortable: true,
  },
  {
    name: "name",
    align: "left",
    label: "Họ và tên",
    field: "name",
    sortable: true,
  },
  {
    name: "object",
    align: "left",
    label: "Dự án",
    field: "object",
    sortable: true,
  },

  {
    name: "OverTime",
    align: "left",
    label: "Thời gian OT",
    sortable: true,
    field: (row) => {
      if(isProxy(row)){
        row = toRaw(row);
        let start = row.start_time;
        let end = row.end_time;
        return start + ' - ' + end;
      }
      return null
    },
  },
  {
    name: "total_time",
    label: "Số giờ",
    align: "left",
    field: "total_time",
    sortable: true,
  },
  {
    name: "total_multi",
    label: "Hệ số giờ",
    align: "left",
    field: "total_multi",
    sortable: true,
  },
  {
    name: "description",
    label: "Mô tả",
    align: "left",
    field: "description",
    sortable: true,
  },
  {
    name: "active",
    label: "Trạng thái",
    align: "left",
    field: "active",
    sortable: true,
    format: (val) => formatStatus(val),
  },
  { name: "act", label: "Hành động", align: "center", field: "act" },
];

const formatStatus = (val) => {
  switch (val) {
    case 1:
      return "Đã duyệt";
    case 2:
      return "Đang chờ";
    case 3:
      return "Dã hủy";
    default:
      return "Vui lòng chọn";
  }
};
const onRequest = (props) => {
  sortTable(props.pagination.sortBy);
  pagination.value.page = props.pagination.page;
  pagination.value.rowsPerPage = props.pagination.rowsPerPage;
  getListData();
};
const {
  rows,
  filter,
  pagination,
  otvote,
  selected,
  isLoading,
  isError,
  getListData,
  createOvertime,
  updateOverTimeVote,
  repairOverTime,
  deleteDataItem,
} = useOTVote();
// Ngày hiện tại
onMounted(() => {
  const today = new Date();
  const year = today.getFullYear();
  const month = String(today.getMonth() + 1).padStart(2, "0");
  const day = String(today.getDate()).padStart(2, "0");
  otvote.date_ot = `${day}-${month}-${year}`;
});
const handleFilterByStatus = () => {
  getListData(pagination.value.page, pagination.value.rowsPerPage);
};
//tính số giờ
const calculateTime = () => {
  const start_time = parseTime(otvote.value.start_time);
  const end_time = parseTime(otvote.value.end_time);
  const diffInMs = end_time - start_time;
  const diffInMinutes = Math.abs(diffInMs / (1000 * 60 * 60));
  otvote.value.total_time = diffInMinutes;
};
const parseTime = (timeString) => {
  const [hour, minute] = timeString.split(":");
  const date = new Date();
  date.setHours(hour);
  date.setMinutes(minute);
  date.setSeconds(0);
  date.setMilliseconds(0);
  return date;
};

const updateOptiondate_type = (val) => {
  otvote.value.date_type = val;
  calculateExtraSalary();
};
const updateOptionShift = (val) => {
  otvote.value.shift = val;
  calculateExtraSalary();
};
//tính hệ số
const calculateExtraSalary = () => {
  if (otvote.value.date_type && otvote.value.shift) {
    let percentage = 100;
    if (otvote.value.date_type === "weekdays" && otvote.value.shift === "day") {
      percentage += 15;
    } else if (
      otvote.value.date_type === "holiday" &&
      otvote.value.shift === "day"
    ) {
      percentage += 30;
    } else if (
      otvote.value.date_type === "offday" &&
      otvote.value.shift === "day"
    ) {
      percentage += 50;
    } else if (
      otvote.value.date_type === "weekdays" &&
      otvote.value.shift === "night"
    ) {
      percentage += 70;
    } else if (
      otvote.value.date_type === "holiday" &&
      otvote.value.shift === "night"
    ) {
      percentage += 100;
    } else if (
      otvote.value.date_type === "offday" &&
      otvote.value.shift === "night"
    ) {
      percentage += 200;
    }

    otvote.value.multi_time = percentage + "%";
  } else {
    otvote.value.multi_time = null;
  }
};
watch(otvote, () => {
  calculateWage();
}, { deep: true });
function calculateWage() {
  const decimalMultiplier = parseFloat(otvote.value.multi_time) / 100
  otvote.value.total_multi = 300 * otvote.value.total_time * decimalMultiplier;
}

const isPopupCreate = ref(false);
const isPopupReport = ref(false);
const isPopupDetails = ref(false);
const isPopupUploadFile = ref(false);
const isPopupDeleteItem = ref(false);
const overtimeId = ref(null);
const isEdit = ref(false)
const handleOpenPopupCreate = () => {
  isPopupCreate.value = true;
  isEdit.value = true;
  otvote.value =({
    date_ot: "",
    date_type: "",
    start_time: "",
    end_time: "",
    total_time: "",
    shift: "",
    name: "",
    client:"",
    object:"",
    multi_time: "",
    description: "",
    active: "",
    approved_by_user: "",
    description:"",
  })
};
const editInfo = (id) => {
  overtimeId.value = id;
  isPopupCreate.value = true;
  isEdit.value =false;
  otvote.value = rows.value.find((it)=> it.id === id);
}
const saveOverTime = async () => {
  isPopupCreate.value = false;
  await createOvertime(otvote.value)
}
const updateOverTime = async () => {
  isPopupCreate.value = false;
  await updateOverTimeVote(overtimeId.value)
}
const repairOverTimeVote = async () => {
  isPopupReport.value = false;
  await repairOverTime(otvote.value);
};
const handleOpenPopupDeleteItem = (id) => {
  isPopupDeleteItem.value = true;
  overtimeId.value = id;
};
const handleDeleteInfoBreak = () => {
  deleteDataItem(overtimeId.value)
  rows.value = rows.value.filter(
    (item) => item.id !== overtimeId.value
  );
  overtimeId.value = null;
  isPopupDeleteItem.value = false;
};
const handleOpenPopupInforItem = (id) => {
  isPopupDetails.value = true;
  otvote.value = rows.value.find((it) => it.id === id);
};
const handleFileUpload = () => {
  isPopupUploadFile.value = true;
};
const handleReportOverTime = (id) => {
  isPopupReport.value = true;
  otvote.value = rows.value.find((it) => it.id === id);
};
onMounted(() => {
  getListData();
});
</script>
<style lang="scss" scoped>
@import "assets/scss/common.scss";
@import "./scss/overtime.scss";
</style>