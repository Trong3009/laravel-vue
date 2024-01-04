<template>
  <div class="block-info">
    <div class="q-container list-form">
      <div class="row q-mt-lg" style="padding-top: 32px">
        <div class="block-title">Danh sách Phiếu Remote</div>
      </div>
      <div class="row q-mt-lg justify-between wrap">
        <div class="row q-gutter-md">
          <q-select
            style="min-width: 115px"
            standout="bg-teal text-white"
            dense
            emit-value
            map-options
            v-model="filter.status"
            :options="[
              { label: 'Tất cả', value: '' },
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
      <q-table
        class="q-mt-lg no-border-radius"
        flat
        :rows="rows"
        :columns="columns"
        virtual-scroll
        :loading="isLoading"
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
                    <li @click="editInfo(props.row.id)">
                      <q-icon name="fa-solid fa-pen" />
                    </li>
                    <li
                      @click="reportOverTime(props.row.id)"
                      v-if="props.row.active === 1"
                    >
                      <q-icon name="fa-solid fa-flag" />
                    </li>
                    <li @click="handleOpenPopupInforItem(props.row.id)">
                      <q-icon name="fa-solid fa-circle-info" />
                    </li>
                    <li
                      v-if="props.row.status === 2"
                      @click="handleOpenPopupDeleteItem(props.row.id)"
                    >
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
      <!-- create data -->
      <q-dialog v-model="isPopupCreate" persistent>
        <q-card style="width: 600px; max-width: 80vw">
          <q-card-section class="q-pb-none">
            <q-form class="q-container id-card">
              <div class="form-group mb-10 form-title__overtime">
                <h3 class="text-grey-10 add__title">
                  {{
                    isEdit
                      ? "Thêm mới phiếu bổ sung công"
                      : "Sửa phiếu bổ sung công"
                  }}
                </h3>
                <q-btn icon="close" flat square dense v-close-popup />
              </div>
              <div class="form-group mb-10 d-flex input-field">
                <q-input
                  outlined
                  :mask="mask"
                  v-model="remote.date_remote"
                  type="date"
                  :dense="true"
                  class="input-field_info onsitedate"
                  style="width: 45%"
                >
                  <template v-slot:before>
                    <label style="font-size: 14px; color: #000" for=""
                      >Ngày</label
                    >
                  </template>
                </q-input>
              </div>
              <div class="form-group mb-10 shift-turn">
                <q-select
                  outlined
                  v-model="remote.shift"
                  :dense="true"
                  :options="[
                    {label: 'Cả ngày',value:'Cả ngày'},
                    {label: 'Ca sáng',value:'Ca sáng'},
                    {label: 'Ca chiều',value:'Ca chiều'},
                  ]"
                  style="width: 45%"
                  class="input-field_info"
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for="">Ca làm việc</label>
                  </template>
                </q-select>
                <q-select
                  outlined
                  v-model="remote.dot_turn"
                  style="width: 45%"
                  :dense="true"
                  :options="[
                    {label:'Ra ca',value:'Ra ca'},
                    {label:'Vào ca',value:'Vào ca'}
                  ]"
                  class="input-field_info"
                >
                  <template v-slot:before>
                    <label style="font-size: 14px" for="">Lượt chấm</label>
                  </template>
                </q-select>
              </div>
              <div class="form-group mb-10 input-field">
                <label for="">Mô tả</label>
                <q-input
                  outlined
                  v-model="remote.description"
                  :dense="true"
                  type="text"
                  style="width: 87%"
                ></q-input>
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
                  @click="saveRemote"
                />
                <q-btn
                  v-else
                  style="background-color: #236674"
                  textColor="white"
                  unelevated
                  label="CHuyển duyệt"
                  @click="updateRemoteVote"
                />
              </q-card-actions>
            </q-form>
          </q-card-section>
        </q-card>
      </q-dialog>
      <!-- Repair remote -->
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
export default {};
</script>
  <script setup>
import { onMounted, ref, toRaw, isProxy } from "vue";
import { useRemoteVote } from "../composables/useRemoteVote";
const isPopupCreate = ref(false);
const isPopupUploadFile = ref(false);
const isEdit = ref(false);
const remoteId = ref(null);
const isPopupDeleteItem = ref(false);
const rowsPerPageOptions = ref([10, 20, 50, 100]);
const columns = [
  {
    name: "date_remote",
    align: "left ",
    label: "Ngày",
    field: "date_remote",
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
    name: "remote_time",
    align: "left",
    label: "Dự án",
    sortable: true,
    field: (row) => {
      if (isProxy(row)) {
        row = toRaw(row);
        let start = row.start_time;
        let end = row.end_time;
        return start + " - " + end;
      }
      return null;
    },
  },
  {
    name: "description",
    align: "left",
    label: "Mô tả",
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
const {
  remote,
  isError,
  isLoading,
  rows,
  pagination,
  getListData,
  createRemote,
  updateRemote,
  deleteDataItem,
  filter,
  selected,
  formatStatus,
} = useRemoteVote();
const handleOpenPopupCreate = () => {
  isEdit.value = true;
  isPopupCreate.value = true;
  remote.value = {
    date_remote: "",
    start_time: "",
    end_time: "",
    description: "",
    file_image_remote: "",
    file_image_remote: "",
  };
};
const saveRemote = async () => {
  isPopupCreate.value = false;
  await createRemote(remote.value);
};
const editInfo = (id) => {
  remoteId.value = id;
  isEdit.value = false;
  isPopupCreate.value = true;
  remote.value = rows.value.find((it) => it.id === id);
};
const updateRemoteVote = async () => {
  isPopupCreate.value = false;
  await updateRemote(remoteId.value);
};
const handleOpenPopupDeleteItem = (id) => {
  isPopupDeleteItem.value = true;
  remoteId.value = id;
};
const handleDeleteInfoBreak = () => {
  deleteDataItem(remoteId.value);
  rows.value = rows.value.filter((item) => item.id !== remoteId.value);
  remoteId.value = null;
  isPopupDeleteItem.value = false;
};
const onRequest = (props) => {
  sortTable(props.pagination.sortBy);
  pagination.value.page = props.pagination.page;
  pagination.value.rowsPerPage = props.pagination.rowsPerPage;
  getListData();
};
const sortTable = (columnName) => {
  if (pagination.value.sortBy === columnName) {
    if (pagination.value.descending === "asc") {
      pagination.value.descending = "desc";
    } else {
      pagination.value.descending = "asc";
    }
  } else {
    pagination.value.sortBy = columnName;
    if (pagination.value.descending === "asc") {
      pagination.value.descending = "desc";
    } else {
      pagination.value.descending = "asc";
    }
  }
};
const handleFilterByStatus = () => {
  getListData(pagination.value.page, pagination.value.rowsPerpage);
};
onMounted(() => {
  getListData();
});
</script>
  <style lang="scss" scoped>
@import "assets/scss/common.scss";
@import "./scss/additional.scss";
</style>