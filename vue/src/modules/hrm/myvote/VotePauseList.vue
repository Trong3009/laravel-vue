<template>
  <div class="block-info">
    <div class="q-container list-form">
      <!-- Start: action list -->
      <div class="row q-mt-lg" style="padding-top: 32px">
        <div class="block-title">Danh sách phiếu xin nghỉ</div>
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
                    <li
                      v-if="props.row.status !== 1"
                      @click="handleOpenPopupUpdate(props.row.id)"
                    >
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
      <!-- <p class="text-number__vote">
        Bạn đã nghỉ <small>{{ pausevote.votes_used }}</small> ngày trên tổng <small>{{ pausevote.total_pause_vote }}</small> ngày, Bạn
        còn <small>{{ pausevote.votes_still }}</small> ngày
      </p> -->
      <!-- End: table -->

      <!-- Start: popup create 1 item -->
      <q-dialog v-model="isPopupCreate" persistent @submit.prevent="submitForm(lateId)">
        <q-card style="width: 500px; max-width: 80vw">
          <q-card-section class="q-pb-none">
            <q-form
              ref="myForm"
              class="q-container id-card">
              <div class="form-group mb-10 form-title__pause">
                <h3 class="text-grey-10 add__title">{{ isEdit ? 'Thêm mới phiếu nghỉ' : 'Sửa phiếu nghỉ' }}</h3>
                <q-btn icon="close" flat square dense v-close-popup />
              </div>
              <div class="form-group d-flex mb-10 pt-30 input-field">
                <label class="label-field" for="">Từ ngày</label>
                <div class="form-data__break d-flex">
                  <q-select
                    outlined
                    v-model="pausevote.since_session"
                    :options="[
                      { label: 'Sáng', value: 'Sáng' },
                      { label: 'Chiều', value: 'Chiều' },
                    ]"
                    :dense="true"
                    lazy-rules
                    :rules="isRequired"
                    class="input-field_info"
                    style="width: 40%"
                    emit-value
                    map-options
                  />
                  <q-input
                    outlined
                    v-model="pausevote.since_date"
                    type="date"
                    :dense="true"
                    class="input-field_info"
                    style="width: 55%"
                    @blur="updateNumberOfDays"
                  ></q-input>
                </div>
              </div>             
              <div class="form-group d-flex mb-10 pt-10 input-field">
                <label class="label-field" for="">Đến ngày</label>
                <div class="form-data__break d-flex">
                  <q-select
                    outlined
                    v-model="pausevote.todate_session"
                    :options="[
                      { label: 'Sáng', value: 'Sáng' },
                      { label: 'chiều', value: 'Chiều' },
                    ]"
                    :dense="true"
                    class="input-field_info"
                    lazy-rules
                    :rules="isRequired"
                    style="width: 40%"
                    emit-value
                    map-options
                  />
                  <q-input
                    outlined
                    v-model="pausevote.todate_date"
                    type="date"
                    :dense="true"
                    lazy-rules
                    :rules="isRequired"
                    class="input-field_info"
                    style="width: 55%"
                    @blur="updateNumberOfDays"
                  ></q-input>
                </div>
              </div>
              <div class="form-group d-flex mb-10 pt-10 input-field">
                <label class="label-field" for="">Hình thức nghỉ</label>
                <q-select
                  outlined
                  v-model="pausevote.types_break"
                  :options="[
                    { label: 'Nghỉ không lương', value: 'Nghỉ không lương' },
                    { label: 'Nghỉ ốm', value: 'Nghỉ ốm' },
                    { label: 'Ghi nợ', value: 'Ghi nợ' },
                    { label: 'Nghỉ phép', value: 'Nghỉ phép' },
                  ]"
                  :dense="true"
                  lazy-rules
                  :rules="isRequired"
                  class="input-field_info"
                  emit-value
                  map-options
                  style="width: 60%"
                  @update:model-value="(value) => updatePercentage(value)"
                />
              </div>
              <div class="form-group d-flex mb-10 pt-10 input-field">
                <label class="label-field" for="">Lý do</label>
                <q-input
                  outlined
                  :dense="true"
                  id=""
                  v-model="pausevote.reason"
                  type="text"
                  lazy-rules
                  :rules="isRequired"
                  class="input-field_info"
                  style="width: 60%"
                />
              </div>
              <q-card-actions
              align="right" style="padding: 20px 0px">
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
      <!-- End: popup details items -->
      <q-dialog v-model="isPopupDetails" persistent>
        <q-card style="width: 500px; max-width: 80vw">
          <q-card-section class="q-pb-none">
            <q-form class="q-container id-card">
              <div class="form-group mb-10 form-title__pause">
                <h3 class="text-grey-10 add__title">Phiếu xin nghỉ</h3>
                <q-btn icon="close" flat square dense v-close-popup />
              </div>
              <div class="form-group d-flex mb-10 pt-30 input-field">
                <label class="label-field" for="">Từ ngày</label>
                <div class="form-data__break d-flex">
                  <q-select outlined v-model="pausevote.since_session" :dense="true" class="input-field_info" style="width: 40%" disable />
                  <q-input outlined v-model="pausevote.since_date" type="date" :dense="true" class="input-field_info" style="width: 55%" disable></q-input>
                </div>
              </div>
              <div class="form-group d-flex mb-10 pt-10 input-field">
                <label class="label-field" for="">Đến ngày</label>
                <div class="form-data__break d-flex">
                  <q-select outlined v-model="pausevote.todate_session" :dense="true" class="input-field_info" style="width: 40%;" disable/>
                  <q-input outlined v-model="pausevote.todate_date" type="date" :dense="true" class="input-field_info" style="width: 55%" disable></q-input>
                </div>
              </div>
              <div class="form-group d-flex mb-10 pt-10 input-field">
                <label class="label-field" for="">Hình thức nghỉ</label>
                <q-select outlined v-model="pausevote.types_break" :dense="true" style="width: 60%" class="input-field_info" disable/>
              </div>
              <div class="form-group d-flex mb-10 pt-10 input-field">
                <label class="label-field" for="">Tỷ lệ hưởng lương</label>
                <q-input outlined :dense="true" type="text" v-model="pausevote.salary_percentege" class="input-field_info" style="width: 60%" disable/>
              </div>
              <div class="form-group d-flex mb-10 pt-10 input-field">
                <label class="label-field" for="">Lý do</label>
                <q-input outlined :dense="true" id="" v-model="pausevote.reason" type="text" class="input-field_info" style="width: 60%" disable />
              </div>
              <div class="form-group d-flex mb-10 pt-10 input-field">
                <label class="label-field" for="">Người duyệt</label>
                <q-select outlined v-model="pausevote.approved_by" :dense="true" class="input-field_info" style="width: 60%" disable></q-select>
              </div>
              <q-card-actions align="between" style="padding: 20px 0px">
                <q-btn style="background: #d9d9d9" @click="handleOPenPopupHistory" label="lịch sừ"/>
                <q-btn style="background-color: #236674" textColor="white" unelevated label="Đóng" v-close-popup />
              </q-card-actions>
            </q-form>
          </q-card-section>
        </q-card>
      </q-dialog>
      <!-- Start: popup history file -->
      <q-dialog v-model="isPopupHistory" persistent>
        <q-card style="width: 640px; max-width: 80vw">
          <q-card-section class="q-pb-none">
            <q-form class="q-container id-card">
              <div class="form-group mb-10 form-title__pause">
                <h3 class="text-grey-10 add__title">Xem lịch sử</h3>
                <q-btn icon="close" flat square dense v-close-popup />
              </div>
              <div class="form-group mb-10 pt-10">
                <q-table
                  :rows="historyRows"
                  :columns="historyColumns"
                  hide-bottom=""
                >
                </q-table>
              </div>
              <q-card-actions align="right" style="padding: 20px 0px">
                <q-btn
                  style="background-color: #236674; padding: 0 16px"
                  textColor="white"
                  unelevated
                  label="Đóng"
                  v-close-popup
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
import { defineComponent } from "vue";
export default defineComponent({
  name: "VotePauseList",
  
  data() {
    // return {
    //   shiftOfJob: null,
    //   model: null,
    //   validateName: [(value) => !!value || "Name is required"],
    //   errorValidate: "",
    //   reason: "",
    //   isRequired: [(val) => !!val || "Không được để trống"],
    //   handleEditInfo: false,
    //   openHandleEditInfo: false,
    //   status: 1,
    // };
  },
});
</script>
<script setup>
import { ref, onMounted, isProxy, toRaw ,watch} from "vue";
import { usePauseVote } from "../composables/usePauseVote";
import { date } from "quasar";
const rowsPerPageOptions = ref([10, 20, 50, 100]);
const columns = [
  {
    name: "name",
    align: "left ",
    label: "Họ và tên",
    field: "name",
    sortable: true,
  },
  {
    name: "since_session",
    align: "left",
    label: "Từ Ngày",
    sortable: true,
    field: (row) => {
        if (isProxy(row)) {
          row = toRaw(row);
          let text = row.since_session;
          let time = row.since_date;
          time = date.formatDate(time, 'DD/MM/YYYY');
          return text + '  ' + time;
        }
        return null;
    }
  },
  {
    name: "todate_session",
    align: "left",
    label: "Đến ngày",
    sortable: true,
    field: (row) => {
        if (isProxy(row)) {
          row = toRaw(row);
          let text = row.todate_session; 
          let time = row.todate_date;
          time = date.formatDate(time, 'DD/MM/YYYY');
          return text + '  ' + time;
        }
        return null;
    },
    
  },
  {
    name: "number_days",
    label: "Số ngày",
    align: "left",
    field: "number_days",
    sortable: true,
  },
  {
    name: "types_break",
    label: "Loại nghỉ",
    align: "left",
    field: "types_break",
    sortable: true,
  },
  {
    name: "reason",
    label: "Lý do",
    align: "left",
    field: "reason",
    sortable: true,
  },
  {
    name: "status",
    label: "Trạng thái",
    align: "left",
    field: "status",
    sortable: true,
    format: (val) => formatStatus(val),
  },
  {
    name: "act",
    label: "Hành động",
    align: "center",
    field: "act",
  },
];

const historyColumns = [
  {
    name: "id",
    label: "STT",
    field: "id",
    align: "center",
    sortable: true,
  },
  {
    name: "historyTime",
    label: "Thời gian",
    field: "historyTime",
    align: "center",
    sortable: true,
  },
  {
    name: "implementer",
    label: "Người thực hiện",
    field: "implementer",
    align: "left",
    sortable: true,
  },
  {
    name: "reasonapprove",
    label: "Lý do",
    field: "reasonapprove",
    align: "left",
    sortable: true,
  },
];
const historyRows = [
  {
    id: 1,
    historyTime: "10/12/2023 10:00",
    implementer: "NhamNT",
    reasonapprove: "khong cho nghi",
  },
];

const isPopupCreate = ref(false);
const isPopupHistory = ref(false);
const isEdit = ref(false)
const isPopupDetails = ref(false);
const isDropdownEdit = ref(false);
const myForm = ref(null);
const pausevoteId = ref(null);
const isPopupDeleteItem = ref(false);

const {
  rows,
  filter,
  pausevote,
  isError,
  selected,
  isLoading,
  pagination,
  getListData,
  createPauseVote,
  updatePauseVote,
  deleteDataItem,
} = usePauseVote();

const updateNumberOfDays = () => {
  if(pausevote.value.since_date && pausevote.value.todate_date){
    const startDate = new Date(pausevote.value.since_date);
    const endDate = new Date(pausevote.value.todate_date);
    const timeDifference = endDate.getTime() - startDate.getTime();
    const dayDifference = Math.ceil(timeDifference / (1000 * 3600 * 24) + 1);
    pausevote.value.number_days = dayDifference;
  }else{  
    pausevote.value.number_days = null;
  }
}
watch[{
  'pausevote.since_date': function(newDate) {
    if (newDate === this.pausevote.todate_date) {
      this.pausevote.since_session = 'Sáng';
      this.pausevote.todate_session = 'Chiều';
    }
  },
  'pausevote.todate_date': function(newDate) {
    if (newDate === this.pausevote.since_date) {
      this.pausevote.since_session = 'Sáng';
      this.pausevote.todate_session = 'Chiều';
    }
  }
}];
function updatePercentage(value) {
  switch (value) {
    case "Nghỉ không lương":
      this.pausevote.salary_percentege = "0%";
      break;
    case "Nghỉ ốm":
      this.pausevote.salary_percentege = "25%";
      break;
    case "Ghi nợ":
      this.pausevote.salary_percentege = "50%";
      break;
    case "Nghỉ phép":
      this.pausevote.salary_percentege = "100%";
      break;
    default:
      this.pausevote.salary_percentege = "";
      break;
  }
}
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
  const { page, rowsPerPage, sortBy, descending } = props.pagination;
  pagination.value.page = page;
  pagination.value.rowsPerPage = rowsPerPage;
  pagination.value.sortBy = sortBy;
  pagination.value.descending = descending;
  getListData();
};

const handleFilterByStatus = () => {
  getListData(pagination.value.page, pagination.value.rowsPerpage);
};
const handleOpenPopupCreate = () => {
  isPopupCreate.value = true;
  isEdit.value = true;
  pausevote.value = {
    since_session: "",
    since_date:"",
    todate_session:"",
    todate_date:"",
    reason:"",
    types_break:"",
  }
};
const handleCreateItem = async () => {
  isPopupCreate.value = false;
  await createPauseVote(pausevote.value);
  console.log(pausevote.value.label);
};
const handleUpdateItem = async () => {
  isPopupCreate.value = false;
  await updatePauseVote(pausevoteId.value) 
}
const handleOPenPopupHistory = () => {
  isPopupHistory.value = true;
};
const handleOpenPopupUpdate = (id) => {
  pausevoteId.value = id;
  isPopupCreate.value = true;
  isEdit.value =false;
  pausevote.value = rows.value.find((it) => it.id === id);
};
const handleOpenPopupDeleteItem = (id) => {
  isPopupDeleteItem.value = true;
  pausevoteId.value = id;
};
const handleDeleteInfoBreak = () => {
  deleteDataItem(pausevoteId.value)
  rows.value = rows.value.filter(
    (item) => item.id !== pausevoteId.value
  );
  pausevoteId.value = null;
  isPopupDeleteItem.value = false;
};
const handleOpenPopupInforItem = (id) => {
  isPopupDetails.value = true;
  pausevote.value = rows.value.find((it) => it.id === id);
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
onMounted(() => {
  getListData();
})
</script>
<style lang="scss" scoped>
@import "./scss/pauselist.scss";
@import "assets/scss/common.scss";
</style>