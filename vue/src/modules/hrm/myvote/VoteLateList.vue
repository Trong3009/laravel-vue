<template>
  <div class="block-info">
    <div class="q-container list-form">
      <!-- Start: action list -->
      <div class="row q-mt-lg" style="padding-top: 32px">
        <div class="block-title">Danh sách phiếu đi muộn</div>
      </div>
      <div class="row q-mt-lg justify-between wrap">
        <div class="row q-gutter-md">
          <q-select
            style="min-width: 115px"
            standout="bg-teal text-white"
            dense
            map-options
            emit-value
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
      <!-- End: action list -->

      <!-- Start: table -->
      <q-table
        class="q-mt-lg no-border-radius"
        flat
        :rows="rows"
        :columns="columns"
        virtual-scroll
        :loading="isLoading"
        selection="multiple"
        :separator="'cell'"
        rows-per-page-label="Hiển thị"
        loading-label="Đang tải..."
        :rows-per-page-options="rowsPerPageOptions"
        v-model:selected="selected"
        hide-bottom=""
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
                    <li @click="handleOpenPopupUpdate(props.row.id)" v-if="props.row.status !== 1">
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
      <!-- End: table -->
      <!-- Tạo và thêm mới phiếu nghỉ -->
      <q-dialog v-model="isPopupCreate" persistent>
        <q-card style="width: 500px; max-width: 80vw">
          <q-card-section class="q-pb-none">
            <form
              class="q-container id-card"
            >
              <div class="form-group mb-10 form-title__pause">
                <h3 class="text-grey-10 add__title">{{ isEdit ? "Thêm mới phiếu đi muộn" : "Sửa phiếu đi muộn" }}</h3>
                <q-btn icon="close" flat square dense v-close-popup />
              </div>
              <div class="form-group d-flex mb-10 pt-10 input-field">
                <label class="label-field" for="">ngày</label>
                <q-input
                  outlined
                  v-model="latevote.late_date"
                  type="date"
                  :dense="true"
                  class="input-field_info"
                  style="width: 60%"
                ></q-input>
              </div>
              <div class="form-group d-flex mb-10 pt-10 input-field">
                <label class="label-field" for="">Khung giờ</label>
                <div class="form-data__break d-flex">
                  <q-input
                    class="input-field_time"
                    outlined
                    :dense="true"
                    v-model="latevote.start_time"
                    type="time"
                    @blur="(e) => calculateTime()"
                  />
                  <q-input
                    class="input-field_time"
                    outlined
                    :dense="true"
                    v-model="latevote.end_time"
                    type="time"
                    @blur="(e) => calculateTime()"
                  />
                </div>
              </div>
              <div class="form-group d-flex mb-10 pt-10 input-field">
                <label class="label-field" for="">Số phút</label>
                <q-input
                  outlined
                  :dense="true"
                  type="text"
                  v-model="latevote.number_minute"
                  class="input-field_info"
                  style="width: 60%"
                  disable
                />
              </div>
              <div class="form-group d-flex mb-10 pt-10 input-field">
                <label class="label-field" for="">Lý do</label>
                <q-input
                  outlined
                  :dense="true"
                  ref="nameRef"
                  v-model="latevote.reason"
                  id=""
                  type="text"
                  class="input-field_info"
                  style="width: 60%"
                />
              </div>
              <q-card-actions align="right" style="padding: 20px 0px">
                <q-btn
                  style="background: #d9d9d9"
                  unelevatedpopup
                  label="Hủy bỏ"
                  flat
                  v-close-popup
                />
                <q-btn
                v-if="isEdit"
                  style="background-color: #236674"
                  color="text-white"
                  :loading="submitting"
                  unelevated
                  type="submit"
                  label="Chuyển duyệt"
                  @click="createLate"
                />
                <q-btn
                v-else
                  style="background-color: #236674"
                  color="text-white"
                  :loading="submitting"
                  unelevated
                  type="submit"
                  label="update"
                  @click="updateLate"
                />
              </q-card-actions>
            </form>
          </q-card-section>
        </q-card>
      </q-dialog>
      <!-- Thông tin phiếu nghỉ -->
      <q-dialog v-model="isPopupDetails" persistent>
        <q-card style="width: 560px; max-width: 80vw">
          <q-card-section class="q-pb-none">
            <form class="q-container id-card">
              <div class="form-group mb-10 form-title__pause">
                <h3 class="text-grey-10 add__title">Thông tin phiếu đi muộn</h3>
                <q-btn icon="close" flat square dense v-close-popup />
              </div>
              <div class="form-group d-flex mb-10 pt-10 input-field">
                <label class="label-field" for="">ngày</label>
                <q-input outlined v-model="latevote.late_date" type="date" :dense="true" class="input-field_info" style="width: 60%" disable></q-input>
              </div>
              <div class="form-group d-flex mb-10 pt-10 input-field">
                <label class="label-field" for="">Ca làm</label>
                <q-select outlined v-model="latevote.shift_job" :dense="true" class="input-field_info" style="width: 60%" disable/>
              </div>
              <div class="form-group d-flex mb-10 pt-10 input-field">
                <label class="label-field" for="">Đến ngày</label>
                <div class="form-data__break d-flex">
                  <q-input class="input-field_time" outlined :dense="true" v-model="latevote.start_time" type="time" disable/>
                  <q-input class="input-field_time" outlined :dense="true" v-model="latevote.end_time" type="time" disable/>
                </div>
              </div>
              <div class="form-group d-flex mb-10 pt-10 input-field">
                <label class="label-field" for="">Số phút</label>
                <q-input outlined :dense="true" type="text" v-model="latevote.number_minute" class="input-field_info" style="width: 60%" disable/>
              </div>
              <div class="form-group d-flex mb-10 pt-10 input-field">
                <label class="label-field" for="">Dự án</label>
                <q-input outlined :dense="true" type="text" v-model="latevote.object" class="input-field_info" style="width: 60%" disable/>
              </div>
              <div class="form-group d-flex mb-10 pt-10 input-field">
                <label class="label-field" for="">Lý do</label>
                <q-input outlined :dense="true" ref="nameRef" v-model="latevote.reason" type="text" class="input-field_info" style="width: 60%" disable/>
              </div>
              <div class="form-group d-flex mb-10 pt-10 input-field">
                <label class="label-field" for="">Người duyệt</label>
                <q-select outlined v-model="latevote.approved_by" :dense="true" class="input-field_info" style="width: 60%" disable/>
              </div>
              <q-card-actions align="right" style="padding: 20px 0px">
                <q-btn style="background: #d9d9d9" unelevatedpopup label="Hủy bỏ" flat v-close-popup
                />
                <q-btn style="background-color: #236674" color="text-white" unelevated type="submit" label="Lưu lại"/>
              </q-card-actions>
            </form>
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
import { ref } from "vue";
import { defineComponent } from "vue";
import { Notify, useQuasar } from "quasar";
export default defineComponent({
  name: "VotePauseList",
  computed: {
    currentPath() {
      return this.$route.path;
    },
  },
  data() {
    return {
      isRequired: [(val) => !!val || "Không được để trống"],
    };
  },
  methods: {
    isRequired() {
      return [(val) => !!val || "ko dc de trong"];
    },
  },
});
</script>
  <script setup>
import { ref, onMounted, isProxy, toRaw, } from "vue";
import { useLateVote } from "../composables/useLateVote.js";
import { date } from "quasar";
const { prop } = defineProps(['prop']);
const rowsPerPageOptions = ref([10, 20, 50, 100]);
const columns = [
  {name: "late_date",align: "left ",label: "ngày",field: "late_date",sortable: true,format: (val) => {return date.formatDate(val, 'DD/MM/YYYY')}},
  {name: "name", align: "left",label: "Họ và tên",field: "name",sortable: true,},
  {
    name: "latetime",
    label: "Thời gian muộn",
    align: "left",
    sortable: true,
    field: (row) => {
      if(isProxy(row)){
        row = toRaw(row);
        let start = row.start_time;
        let end = row.end_time;
        return start + ' -- ' + end;
      }
      return null
    }, 
  },
  {name: "number_inute",label: "Số phút",align: "left",field: "number_minute",sortable: true,},
  {name: "reason",label: "Lý do",align: "left",field: "reason",sortable: true,},
  {name: "active",label: "Trạng thái",align: "left",field: "status",sortable: true,format: (val) => formatStatus(val),},
  {name: "act", label: "Hành động", align: "center", field: "act",},
];
const isPopupCreate = ref(false);
const isPopupDetails = ref(false);
const isEdit =ref(false)
const isDropdownEdit = ref(false);
const lateVoteId = ref(null);
const isPopupDeleteItem = ref(false);

const calculateTime = () => {
  const start_time = parseTime(latevote.value.start_time);
  const end_time = parseTime(latevote.value.end_time);
  const diffInMs = end_time - start_time;
  const diffInMinutes = Math.abs(diffInMs / (1000 * 60));
  latevote.value.number_minute = diffInMinutes;
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

const createLate = async () => {
  isPopupCreate.value = false;
  await createLateVote(latevote.value)
  console.log(latevote.value)
};
const updateLate = async () => {
  isPopupCreate.value = false;
  await updateItem(lateVoteId.value)
  console.log(latevote.value)
}
const handleOpenPopupCreate = () => {
  isPopupCreate.value = true;
  isEdit.value = true;
  latevote.value = {
    name: "",
    since: "",
    todate: "",
    number_minute: "",
    object: "",
    reason: "",
    active: "",
    start_time: "",
    end_time: "",
  };
};
const handleOpenPopupDeleteItem = (id) => {
  isPopupDeleteItem.value = true;
  lateVoteId.value = id;
};
const handleDeleteInfoBreak = () => {
  deleteDataItem(lateVoteId.value)
  rows.value = rows.value.filter(
    (item) => item.id !== lateVoteId.value
  );
  lateVoteId.value = null;
  isPopupDeleteItem.value = false;
};
const {
  rows,
  isError,
  filter,
  selected,
  isLoading,
  pagination,
  getListData,
  updateItem,
  createLateVote,
  formatStatus,
  deleteDataItem,
  latevote
} = useLateVote();

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

const handleFilterByStatus = () => {
  getListData(pagination.value.page, pagination.value.rowsPerPage);
};
const handleOpenPopupUpdate = (id) => {
  lateVoteId.value = id;
  isPopupCreate.value = true;
  isEdit.value = false;
  latevote.value = rows.value.find((it) => it.id === id);
}
const handleOpenPopupInforItem = (id) => {
  isPopupDetails.value = true;
  latevote.value = rows.value.find((it) => it.id === id);
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
const submitting = ref(false);
const nameRef = ref(null);
onMounted(() => {
  getListData();
})
</script>
<style lang="scss" scoped>
@import "./scss/latelist.scss";
@import "assets/scss/common.scss";
</style>