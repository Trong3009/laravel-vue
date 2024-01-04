<template>
  <div class="block-info">
    <div class="q-container">
      <!-- Start: action list -->
      <div class="row q-mt-lg justify-between" style="position: relative">
        <div class="block-title">Quản lý phép</div>
      </div>
      <div class="row q-mt-lg justify-between wrap">
        <div class="row q-gutter-md">
          <q-select
            style="min-width: 155px"
            standout="bg-teal text-white"
            dense
            v-model="model"
            :options="options"
            label="Khoa Khám bệnh"
          />
        </div>
        <div class="row justify-end">
          <div class="row date-time">
            <div>Tháng 7/2023</div>
            <div><q-icon name="fa-solid fa-angle-down" /></div>
          </div>
          <div style="min-width: 320px; margin-left: 5px">
            <q-input v-model="search" dense outlined placeholder="Tìm kiếm">
              <template v-slot:append>
                <q-icon name="search" />
              </template>
            </q-input>
          </div>
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
        hide-pagination=""
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
            <div class="title-del">Xóa dữ liệu bảng phép</div>
            <q-space />
          </q-card-section>

          <q-card-section style="text-align: center"
            >Bạn muốn xóa dữ liệu phép đã chọn ?</q-card-section
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
    </div>
  </div>
</template>

<script>
import { defineComponent, ref } from "vue";

function wrapCsvValue(val, formatFn, row) {
  let formatted = formatFn !== void 0 ? formatFn(val, row) : val;

  formatted =
    formatted === void 0 || formatted === null ? "" : String(formatted);

  formatted = formatted.split('"').join('""');

  return `"${formatted}"`;
}

export default defineComponent({
  name: "AggregateWork",
});
</script>

<script setup>
const columns = [
  {
    name: "anh",
    label: "Ảnh",
    align: "center",
    field: "anh",
    sortable: true,
  },
  {
    name: "employeecode",
    required: true,
    label: "Mã NV",
    align: "left",
    field: (row) => row.name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "fullname",
    align: "left ",
    label: "Họ tên",
    field: "fullname",
    sortable: true,
  },
  {
    name: "part",
    align: "left ",
    label: "Phòng Ban/ Bộ Phận",
    field: "part",
    sortable: true,
  },
  {
    name: "officialdate",
    align: "left ",
    label: "Ngày Chính Thức",
    field: "officialdate",
    sortable: true,
  },
  {
    name: "seniority",
    align: "left ",
    label: "Thâm Niên",
    field: "seniority",
    sortable: true,
  },
  {
    name: "paidleave",
    align: "left ",
    label: "Số PN",
    field: "paidleave",
    sortable: true,
  },
  {
    name: "holiday",
    align: "left ",
    label: "Số PN Đến Tháng Hiện Tại",
    field: "holiday",
    sortable: true,
  },
  {
    name: "someholidays",
    align: "left ",
    label: "Số Ngày Nghỉ",
    field: "someholidays",
    sortable: true,
  },
  {
    name: "manyleave",
    align: "left ",
    label: "Nghỉ Quá Số Phép Đến Tháng Hiện Tại",
    field: "manyleave",
    sortable: true,
  },
  {
    name: "remainingleave",
    align: "left ",
    label: "Số Phép Năm Con Lại",
    field: "remainingleave",
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
    id: "1",
    anh: "../src/assets/avatar2.png",
    name: "NS-025",
    fullname: "Đàm Vĩnh Hưng",
    part: "Khoa Lâm sàng/ Khoa Khúc xạ",
    officialdate: "16/08/2023",
    seniority: "8",
    paidleave: 12,
    holiday: 6,
    someholidays: 2,
    manyleave: 0,
    remainingleave: 11,
    act: "",
  },
  {
    id: "2",
    anh: "../src/assets/avatar2.png",
    name: "NS-026",
    fullname: "Nguyễn Ngọc Hà",
    part: "Khoa Lâm sàng/ Khoa Khúc xạ",
    officialdate: "16/08/2023",
    seniority: "5",
    paidleave: 13,
    holiday: 5,
    someholidays: 1,
    manyleave: 0,
    remainingleave: 10,
    act: "",
  },
]);

const isShowPopupDelete = ref(false);
const isShowPopupInfosDelete = ref(false);
const selected = ref([]);
const isPopupDeleteItem = ref(false);

const code = ref("");
const search = ref("");

const handleDeleteInfosSelected = () => {
  isShowPopupInfosDelete.value = false;
  aggregateList.value = aggregateList.value.filter((item) => {
    return !selected.value.some((el) => item.name === el.name);
  });
  selected.value = [];
};

const handleClosePopupInfosDelete = () => {
  isShowPopupInfosDelete.value = false;
};

const handleClosePopupDelete = (e) => {
  isShowPopupDelete.value = e;
  code.value = "";
};

const handleOpenPopupDeleteInfo = (e) => {
  isShowPopupDelete.value = true;
  code.value = e;
};

const handleOpenPopupDeleteInfos = () => {
  isShowPopupInfosDelete.value = true;
};

const handleOpenPopupDeleteItem = () => {
  isPopupDeleteItem.value = true;
};

const handleDeleteInfo = () => {
  aggregateList.value = aggregateList.value.filter(
    (item) => item.name !== code.value
  );
  isShowPopupDelete.value = false;
};

const getSelectedInfosDelete = (e) => {
  selected.value = [...e];
};
</script>

<style lang="scss" scoped>
@import "./assest/EmployeeTimeKeepingComponent";
@import "assets/scss/common.scss";
@import "./assest/AggregateWork.scss";
</style>
