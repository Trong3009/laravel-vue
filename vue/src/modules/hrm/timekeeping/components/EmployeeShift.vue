<template>
  <div>
    <div class="block-info__new">
      <div style="font-weight: 700">Danh sách ca làm việc</div>
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
          style="background-color: #236674; color: #fff"
          nelevated
          label="Thêm mới"
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
          <div class="title-del">Xóa ca làm việc</div>
          <q-space />
        </q-card-section>

        <q-card-section style="text-align: center"
          >Bạn muốn xóa ca làm việc đã chọn ?</q-card-section
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
            @click="handleDeleteInfo"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
    <!-- End: popup delete 1 item -->

    <!-- Start: popup delete items -->
    <q-dialog v-model="isShowPopupInfosDelete" persistent>
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
          <div class="title-del">Xóa thông tin các ca làm việc</div>
          <q-space />
        </q-card-section>

        <q-card-section style="text-align: center"
          >Bạn muốn xóa thông tin các các ca làm việc đã chọn?</q-card-section
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
            @click="handleDeleteInfosSelected"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
    <!-- End: popup delete items -->
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
  name: "EmployeeShift",
});
</script>

<script setup>
const columns = [
  {
    name: "shiftcode",
    align: "left ",
    label: "Mã Ca",
    field: "shiftcode",
    sortable: true,
  },
  {
    name: "shiftname",
    align: "left ",
    label: "Tên Ca",
    field: "shiftname",
    sortable: true,
  },
  {
    name: "applicableparts",
    align: "left",
    label: "Bộ Phận Áp Dụng",
    field: "applicableparts",
    sortable: true,
  },
  {
    name: "shifttime",
    align: "left",
    label: "Thời Gian Ca",
    field: "shifttime",
    sortable: true,
  },
  {
    name: "datecreated",
    label: "Ngày Tạo",
    align: "left",
    field: "datecreated",
    sortable: true,
  },
  {
    name: "status",
    label: "Trạng Thái",
    align: "left",
    field: "status",
    sortable: true,
  },
  {
    name: "act",
    label: "Thao Tác",
    align: "center",
    field: "act",
  },
];

const rows = ref([
  {
    shiftcode: "000001",
    shiftname: "Ca sáng",
    applicableparts: "Tất cả",
    shifttime: "07:00-16:00",
    datecreated: "15/08/2023",
    status: "Đang hoạt động",
    act: "",
  },
  {
    shiftcode: "000001",
    shiftname: "Ca chiều",
    applicableparts: "Tất cả",
    shifttime: "08:30-17:30",
    datecreated: "15/08/2023",
    status: "Đang hoạt động",
    act: "",
  },
]);

const isShowPopupInfosDelete = ref(false);
const isPopupDeleteItem = ref(false);
const selected = ref([]);

const code = ref("");
const search = ref("");



const handleOpenPopupDeleteItem = () => {
  isPopupDeleteItem.value = true;
};

const handleDeleteInfosSelected = () => {
  isShowPopupInfosDelete.value = false;
  shiftList.value = shiftList.value.filter((item) => {
    return !selected.value.some((el) => item.name === el.name);
  });
  selected.value = [];
};

const handleOpenPopupDeleteInfos = () => {
  isShowPopupInfosDelete.value = true;
};

const handleDeleteInfo = () => {
  rows.value = rows.value.filter((item) => item.name !== code.value);
  isPopupDeleteItem.value = false;
};

</script>

<style lang="scss" scoped>
@import "assets/scss/common.scss";
@import "../assest/EmployeeTimeKeepingComponent.scss";
</style>
