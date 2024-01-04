<template>
  <div>
    <div class="block-info__new">
      <div>
        <span style="font-weight: bold; margin-right: 14px"
          >Lịch sử chấm công</span
        >
        <span style="font-style: italic; font-size: 15px; color: #ccc"
          >Cập nhật lần cuối vào: 14/07/2023 13:00</span
        >
      </div>
      <div class="list-button">
        <q-btn
          @click="handleOpenPopupDeleteInfos"
          label="Xóa"
          color="red"
          class="button-delete"
          no-caps
          :disable="selected.length && rows.length ? false : true"
        />
        <q-btn
          label="Bổ sung"
          style="background-color: #236674; color: #fff"
          no-caps
        />
        <q-btn
          @click.prevent="handleDownload"
          nelevated
          style="background-color: #236674; color: #fff"
          label="Tải xuống"
          no-caps
        />
        <q-btn
          style="background-color: #236674; color: #fff"
          nelevated
          label="Cập nhật dữ liệu"
          no-caps
        />
      </div>
    </div>
    <div class="row q-mt-lg justify-between wrap">
      <div class="row q-gutter-md">
        <q-select
          style="min-width: 105px"
          standout="bg-teal text-white"
          dense
          v-model="model"
          :options="options"
          label="Bộ phận"
        />
        <q-select
          style="min-width: 155px"
          standout="bg-teal text-white"
          dense
          v-model="model"
          :options="options"
          label="Khoảng thời gian"
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
                  <li @click="handleOpenPopupDeleteItem()">
                    <q-icon name="fa-solid fa-trash-can" />
                  </li>
                  <li @click="handleOpenPopupInforItem(props.row.id)">
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
          <div class="title-del">Xóa dữ liệu quẹt thẻ</div>
          <q-space />
        </q-card-section>

        <q-card-section style="text-align: center"
          >Bạn muốn xóa dữ liệu quẹt thẻ đã chọn ?</q-card-section
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
          <div class="title-del">Xóa thông tin các nhân viên</div>
          <q-space />
        </q-card-section>

        <q-card-section style="text-align: center"
          >Bạn muốn xóa thông tin các nhân viên đã chọn?</q-card-section
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
  </div>
</template>

<script >
import { defineComponent, ref } from "vue";
// import TableInfoTypeOne from "./table/TableInfoTypeOne.vue";
import { useRouter } from "vue-router";

function wrapCsvValue(val, formatFn, row) {
  let formatted = formatFn !== void 0 ? formatFn(val, row) : val;

  formatted =
    formatted === void 0 || formatted === null ? "" : String(formatted);

  formatted = formatted.split('"').join('""');

  return `"${formatted}"`;
}

export default defineComponent({
  name: "EmployeeCardSwipeData",
});
</script>

<script setup>
const columns = [
  {
    name: "employeecode",
    align: "left ",
    label: "Mã NV",
    field: "employeecode",
    sortable: true,
  },
  {
    name: "personnel",
    align: "left ",
    label: "Nhân Viên",
    field: "personnel",
    sortable: true,
  },
  {
    name: "department",
    align: "left",
    label: "Phòng Ban",
    field: "department",
    sortable: true,
  },
  {
    name: "position",
    align: "left",
    label: "Chức Vụ",
    field: "position",
    sortable: true,
  },
  {
    name: "date",
    label: "Ngày",
    align: "left",
    field: "date",
    sortable: true,
  },
  {
    name: "day",
    label: "Thứ",
    align: "left",
    field: "day",
    sortable: true,
  },
  {
    name: "shift",
    label: "Ca Làm Việc",
    align: "left",
    field: "shift",
    sortable: true,
  },
  {
    name: "scantime",
    label: "Giờ Quét",
    align: "left",
    field: "scantime",
    sortable: true,
  },
  {
    name: "inout",
    label: "Vào/ Ra",
    align: "left",
    field: "inout",
    sortable: true,
  },
  {
    name: "timekeeper",
    label: "Máy Chấm",
    align: "left",
    field: "timekeeper",
    sortable: true,
  },
  {
    name: "act",
    label: "Hành động",
    align: "center",
    field: "act",
  },
];

const rows = ref([
  {
    employeecode: "BS0001",
    personnel: "Lê Minh Tuấn",
    department: "Khoa Khám bệnh",
    position: "Bác sĩ",
    date: "15/08/2023",
    day: "Thứ 2",
    shift: "Ca sáng",
    scantime: "08:00:00",
    inout: "Vào ca",
    timekeeper: "T1B",
    act: "",
  },
  {
    employeecode: "BS0001",
    personnel: "Lê Minh Khôi",
    department: "Khoa Khám bệnh",
    position: "Y tá",
    date: "15/08/2023",
    day: "Thứ 2",
    shift: "Ca sáng",
    scantime: "08:00:00",
    inout: "Vào ca",
    timekeeper: "T1B",
    act: "",
  },
]);

const selected = ref([]);
const search = ref("");
const isPopupDeleteItem = ref(false);
const isPopupDeleteItems = ref(false);

const handleOpenPopupDeleteItem = () => {
  isPopupDeleteItem.value = true;
};

const handleOpenPopupDeleteInfos = () => {
  isPopupDeleteItems.value = true;
};

</script>



<style scoped lang="scss">
@import "assets/scss/common.scss";
@import "../assest/EmployeeTimeKeepingComponent.scss";
</style>
