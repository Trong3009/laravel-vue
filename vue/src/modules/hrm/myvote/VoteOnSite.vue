<template>
    <div class="block-info">
      <div class="q-container list-form">
        <div class="row q-mt-lg" style="padding-top: 32px">
          <div class="block-title">Danh sách Phiếu làm ngoài giờ</div>
        </div>
        <div class="row q-mt-lg justify-between wrap">
          <div class="row q-gutter-md">
            <q-select
              style="min-width: 115px"
              standout="bg-teal text-white"
              dense
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
        <q-table
          flat
          class="q-mt-lg no-border-radius"
          :rows="rows"
          :columns="columns"
          :loading="isLoading"
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
                      <li @click="handleOpenPopupUpdate(props.row.id)">
                        <q-icon name="fa-solid fa-pen" />
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
          <q-card style="width: 600px; max-width: 80vw">
            <q-card-section class="q-pb-none">
              <q-form class="q-container id-card">
                <div class="form-group mb-10  input-field">
                  <h3 class="text-grey-10 add__title">
                    {{isEdit ? 'Thêm mới phiếu phiếu onsite': 'Sửa phiếu onsite'}}
                  </h3>
                  <q-btn icon="close" flat square dense v-close-popup />
                </div>
                <div class="form-group mb-10 d-flex input-field">
                    <q-input outlined
                        :mask="mask"
                        v-model="onsite.dateOnsite" type="date" :dense="true" class="input-field_info onsitedate" placeholder="dd/mm/yyyy">
                        <template v-slot:before>
                            <label style="font-size: 14px; color: #000;" for="">Ngày</label>
                        </template>
                    </q-input>
                    <q-input outlined v-model="onsite.time1" type="time" :dense="true" class="input-field_info">
                        <template v-slot:before>
                            <label style="font-size: 14px;" for="">Từ</label>
                        </template>
                    </q-input>
                    <q-input outlined v-model="onsite.time2" type="time" :dense="true" class="input-field_info">
                        <template v-slot:before>
                            <label style="font-size: 14px;" for="">Đến</label>
                        </template>
                    </q-input>
                </div>
                <div class="form-group mb-10 ot-icon">
                  <q-file
                    v-model="onsite.file_onsite"
                      label="Thêm"
                      style="max-width: 300px;height: 48px;overflow: hidden;"
                       >
                    <template v-slot:prepend>
                      <q-icon name="add" class="circle-icon" />
                    </template>
                  </q-file>
                </div>
                <div class="form-group mb-10 input-field">
                    <label for="">Dự án</label>
                    <q-input outlined v-model="onsite.object" :dense="true" type="text" style="width: 80%;"></q-input>
                </div>
                <div class="form-group mb-10 input-field">
                    <label for="">Địa điểm</label>
                    <q-input outlined v-model="onsite.location" :dense="true" type="text" style="width: 80%;"></q-input>
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
                  label="Chuyển duyệt"
                  @click="handleCreateItem"
                />
                <q-btn
                v-else
                  style="background-color: #236674"
                  textColor="white"
                  unelevated
                  label="Chuyển duyệt"
                  @click="handleUpdateItem"
                />
                </q-card-actions>
              </q-form>
            </q-card-section>
          </q-card>
        </q-dialog>
        <!-- repair ónite -->
        <q-dialog v-model="isPopupRepair" persistent>
        <q-card style="width: 600px; max-width: 80vw">
          <q-card-section class="q-pb-none">
            <q-form class="q-container id-card">
              <div class="form-group mb-10 form-title__overtime">
                <h3 class="text-grey-10 add__title">
                  Báo cáo onsite
                </h3>
                <q-btn icon="close" flat square dense v-close-popup />
              </div>
              <div class="form-group mb-10 d-flex input-field">
                    <q-input outlined
                        :mask="mask"
                        v-model="onsite.date_remote" type="date" :dense="true" class="input-field_info onsitedate" placeholder="dd/mm/yyyy">
                        <template v-slot:before>
                            <label style="font-size: 14px; color: #000;" for="">Ngày</label>
                        </template>
                    </q-input>
                    <q-input outlined v-model="onsite.start_time" type="time" :dense="true" class="input-field_info">
                        <template v-slot:before>
                            <label style="font-size: 14px;" for="">Từ</label>
                        </template>
                    </q-input>
                    <q-input outlined v-model="onsite.end_time" type="time" :dense="true" class="input-field_info">
                        <template v-slot:before>
                            <label style="font-size: 14px;" for="">Đến</label>
                        </template>
                    </q-input>
                </div>
                <div class="form-group mb-10 ot-icon">
                  <q-file
                    v-model="onsite.date_onsite"
                      label="Thêm"
                      style="max-width: 300px;height: 48px;overflow: hidden;"
                       >
                    <template v-slot:prepend>
                      <q-icon name="add" class="circle-icon" />
                    </template>
                  </q-file>
                <label style="color: #236674" for="">Thêm</label>
                </div>
                <div class="form-group mb-10 input-field">
                    <label for="">Dự án</label>
                    <q-input outlined v-model="onsite.description" :dense="true" type="text" style="width: 80%;"></q-input>
                </div>
                <div class="form-group mb-10 input-field">
                    <label for="">Người duyệt</label>
                    <q-select outlined v-model="onsite.approved_by" :dense="true" style="width: 80%;"></q-select>
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
                  label="Chuyển duyệt"
                  @click="saverepairOnsite"
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
  export default {};
  </script>
  <script setup>
  import { ref, onMounted,isProxy,toRaw, watch, computed } from "vue";
  import { useRoute } from "vue-router";
  import { useOnsiteVote } from "../composables/useOnsiteVote";
  const columns = [
    {name: "dateOnsite",align: "left ",label: "ngày",field: "date_onsite",sortable: true,},
    {name: "name",align: "left",label: "Họ và tên",field: "name",sortable: true,},
    {
      name: "onsiteTime",
      align: "left",
      label: "Thời gian",
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
    {name: "object",align: "left",label: "Dự án",field: "object",sortable: true,},
    {name: "location",label: "Địa điểm",align: "left",field: "location",sortable: true,},
    {name: "active",label: "Trạng thái",align: "left",field: "active",sortable: true,format: (val) => formatStatus(val),},
    {name: "act", label: "Hành động", align: "center", field: "act" },
  ];
  const isPopupCreate = ref(false);
  const onsiteId = ref(null);
  const isEdit = ref(false);
  const isPopupDeleteItem = ref(false);
  const {
    rows,selected,pagination,isLoading,isError,filter,getListData,createOnsiteVote,updateOnsite,deleteDataItem,onsite,formatStatus
  } = useOnsiteVote();
  
  const handleOpenPopupCreate = () => {
    isPopupCreate.value = true;
    isEdit.value = true;
    onsite.value ={
        dateOnsite:"",
        name:"",
        onsiteTime:"",
        object:"",
        location:"",
        active:"",
        fileOnsite:"",
    }
  };
  const handleFilterByStatus = () => {
  getListData(pagination.value.page, pagination.value.rowsPerpage);
};
const handleCreateItem = async () => {
  isPopupCreate.value = false;
  await createOnsiteVote(onsite.value);
};
const handleUpdateItem = async () => {
  isPopupCreate.value = false;
  await updateOnsite(onsiteId.value)
}
const handleOpenPopupUpdate = (id) => {
  onsiteId.value = id;
  isPopupCreate.value = true;
  isEdit.value =false;
  onsite.value = rows.value.find((it) => it.id === id);

}
const handleOpenPopupDeleteItem = (id) => {
  isPopupDeleteItem.value = true;
  onsiteId.value = id;
};
const handleDeleteInfoBreak = () => {
  deleteDataItem(pausevoteId.value)
  rows.value = rows.value.filter(
    (item) => item.id !== onsiteId.value
  );
  onsiteId.value = null;
  isPopupDeleteItem.value = false;
};
  const mask = '##/##/####';
  const formattedDateOnsite = computed(() => {
  const date = onsite.dateOnsite.value;
  if (!date) return '';

  const [year, month, day] = date.split('-');
  return `${day}/${month}/${year}`;
});
const onRequest = (props) => {
  sortTable(props.pagination.sortBy);
  pagination.value.page = props.pagination.page;
  pagination.value.rowsPerPage = props.pagination.rowsPerPage;
  getListData();
};
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
onMounted(() => {
  getListData();
})
  </script>
  
  <style lang="scss" scoped>
  @import "assets/scss/common.scss";
  @import "./scss/onsite.scss";
  </style>