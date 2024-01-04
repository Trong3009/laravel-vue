<template>
  <div>
    <div class="block-info__new">
      <div style="font-weight: 700; margin: 20px 0">Lịch làm việc</div>
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
        <q-select
          style="min-width: 125px"
          standout="bg-teal text-white"
          dense
          v-model="model"
          :options="options"
          label="Ca làm việc"
        />
      </div>
      <div class="row justify-end">
        <div class="row date-time">
          <div>Tháng 7/2023</div>
          <div><q-icon name="fa-solid fa-angle-down" /></div>
        </div>
        <div style="margin: 0 6px" class="row date-time">
          <div>Tuần</div>
          <div><q-icon name="fa-solid fa-angle-down" /></div>
        </div>
        <div class="row date-today">
          <div><q-icon name="fa-solid fa-angle-left" /></div>
          <div>Hôm nay</div>
          <div><q-icon name="fa-solid fa-angle-right" /></div>
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
    <div class="table-schedule">
      <table>
        <thead>
          <tr>
            <th>
              <button
                @click="handleShowPopupArrangeShift"
                class="btn-add-schedule"
              >
                <q-icon name="fa-solid fa-plus" />
              </button>
            </th>
            <th>
              <span>Thứ hai</span> <br />
              <span>22</span>
            </th>
            <th>
              <span>Thứ ba</span> <br />
              <span>23</span>
            </th>
            <th>
              <span>Thứ tư</span> <br />
              <span>24</span>
            </th>
            <th>
              <span>Thứ năm</span> <br />
              <span>25</span>
            </th>
            <th>
              <span>Thứ sau</span> <br />
              <span>26</span>
            </th>
            <th>
              <span>Thứ bảy</span> <br />
              <span>27</span>
            </th>
            <th>
              <span>CN</span> <br />
              <span>28</span>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Đàm Vĩnh Hưng</td>
            <td>
              <div style="background: #ffffcf" class="shift-work">Ca sáng</div>
            </td>
            <td>
              <div style="background: #d7abff" class="shift-work">Ca tối</div>
            </td>
            <td>
              <div style="background: #fae3ff" class="shift-work">Ca đêm</div>
            </td>
            <td>
              <div style="background: #ffabab" class="shift-work">Nghỉ</div>
            </td>
            <td>
              <div style="background: #fec9dd" class="shift-work">Ca ngày</div>
            </td>
            <td>
              <div style="background: #bdfcc3" class="shift-work">Ca chiều</div>
            </td>
            <td>
              <div style="background: #ffabab" class="shift-work">Nghỉ</div>
            </td>
          </tr>
          <tr>
            <td>Nguyễn Ngọc Hà</td>
            <td>
              <div style="background: #ffffcf" class="shift-work">Ca sáng</div>
            </td>
            <td>
              <div style="background: #d7abff" class="shift-work">Ca tối</div>
            </td>
            <td>
              <div style="background: #fae3ff" class="shift-work">Ca đêm</div>
            </td>
            <td>
              <div style="background: #ffabab" class="shift-work">Nghỉ</div>
            </td>
            <td>
              <div style="background: #fec9dd" class="shift-work">Ca ngày</div>
            </td>
            <td>
              <div style="background: #bdfcc3" class="shift-work">Ca chiều</div>
            </td>
            <td>
              <div style="background: #ffabab" class="shift-work">Nghỉ</div>
            </td>
          </tr>
          <tr>
            <td>Nguyễn Tiến Dự</td>
            <td>
              <div style="background: #ffffcf" class="shift-work">Ca sáng</div>
            </td>
            <td>
              <div style="background: #d7abff" class="shift-work">Ca tối</div>
            </td>
            <td>
              <div style="background: #fae3ff" class="shift-work">Ca đêm</div>
            </td>
            <td>
              <div style="background: #ffabab" class="shift-work">Nghỉ</div>
            </td>
            <td>
              <div style="background: #fec9dd" class="shift-work">Ca ngày</div>
            </td>
            <td>
              <div style="background: #bdfcc3" class="shift-work">Ca chiều</div>
            </td>
            <td>
              <div style="background: #ffabab" class="shift-work">Nghỉ</div>
            </td>
          </tr>
          <tr>
            <td>Phạm Thị Thoại</td>
            <td>
              <div style="background: #ffffcf" class="shift-work">Ca sáng</div>
            </td>
            <td>
              <div style="background: #d7abff" class="shift-work">Ca tối</div>
            </td>
            <td>
              <div style="background: #fae3ff" class="shift-work">Ca đêm</div>
            </td>
            <td>
              <div style="background: #ffabab" class="shift-work">Nghỉ</div>
            </td>
            <td>
              <div style="background: #fec9dd" class="shift-work">Ca ngày</div>
            </td>
            <td>
              <div style="background: #bdfcc3" class="shift-work">Ca chiều</div>
            </td>
            <td>
              <div style="background: #ffabab" class="shift-work">Nghỉ</div>
            </td>
          </tr>
        </tbody>
      </table>
      <q-dialog v-model="isShowPopupArrangeShift">
        <q-card style="width: 300px">
          <q-card-section>
            <div style="font-weight: bold">Xếp ca làm việc</div>
          </q-card-section>

          <q-card-section class="q-pt-none">
            <div class="row items-center q-gutter-xs">
              <q-icon name="fa-solid fa-users" />
              <select value="">
                <option value="">Phòng Khám Bệnh</option>
              </select>
            </div>
            <div class="row items-center q-gutter-xs">
              <q-icon name="fa-solid fa-calendar-days" />
              <input class="input-control" type="text" />
            </div>
            <div class="row items-center q-gutter-xs">
              <q-icon name="fa-solid fa-user-plus" />
              <div class="add-employee">
                <div>Thêm nhân viên vào ca</div>
              </div>
            </div>
            <div class="row items-center q-gutter-xs">
              <q-icon name="fa-solid fa-users" />
              <textarea
                placeholder="Thêm mô tả"
                class="input-control"
              ></textarea>
            </div>
          </q-card-section>

          <q-card-actions align="right" class="bg-white text-teal">
            <q-btn flat label="OK" v-close-popup />
          </q-card-actions>
        </q-card>
      </q-dialog>
    </div>
    <q-dialog v-model="isShowPopupArrangeShift">
      <q-card style="width: 430px">
        <q-card-section>
          <div style="font-weight: bold">Xếp ca làm việc</div>
        </q-card-section>

        <q-card-section class="q-pt-none">
          <div class="row items-center q-gutter-xs">
            <q-icon name="fa-solid fa-users" />
            <select class="select-address" value="">
              <option value="">Phòng Khám Bệnh</option>
            </select>
          </div>
          <div class="row items-center q-gutter-xs" style="margin-top: 15px">
            <q-icon name="fa-solid fa-calendar-days" />
            <div class="shift-date">
              <input class="input-control" type="date" />
              <select class="select-control" value="">
                <option value="">Ca sáng (07:00-16:00)</option>
                <option value="">Ca ngày (08:30-17:30)</option>
                <option value="">Ca chiều (11:00-20:00)</option>
                <option value="">Ca tối (16:00-24:00)</option>
                <option value="">Ca đêm (00:00-9:00)</option>
                <option value="">Nghỉ</option>
                <option style="color: blue">+ Thêm mới</option>
              </select>
            </div>
          </div>
          <div
            class="row items-center q-gutter-xs"
            style="align-items: baseline"
          >
            <q-icon name="fa-solid fa-user-group" />
            <div class="add-staff_shift">Thêm nhân viên vào ca</div>
          </div>
          <div class="staff-shift">
            <div
              class="staff-info"
              @mouseenter="handleHover"
              @mouseleave="handleHover"
            >
              <div class="name-ava">
                <img
                  src="../../assets/images/ellipse-ava.png"
                  alt="ellipse-ava"
                />
                <p>Phạm Hải Nam</p>
              </div>
              <q-icon name="close" v-show="isHover" class="icon-close"></q-icon>
            </div>
            <div
              class="staff-info"
              @mouseenter="handleHover"
              @mouseleave="handleHover"
            >
              <div class="name-ava">
                <img
                  src="../../assets/images/ellipse-ava.png"
                  alt="ellipse-ava"
                />
                <p>Phan Thùy Tiên</p>
              </div>
            </div>
            <div class="staff-info">
              <div class="name-ava">
                <img
                  src="../../assets/images/ellipse-ava.png"
                  alt="ellipse-ava"
                />
                <p>Quế Ngọc Hải</p>
              </div>
            </div>
            <div class="staff-info">
              <div class="name-ava">
                <img
                  src="../../assets/images/ellipse-ava.png"
                  alt="ellipse-ava"
                />
                <p>Dương Thùy Phương</p>
              </div>
            </div>
          </div>
          <div class="row items-center q-gutter-xs" style="margin-top: 15px">
            <q-icon name="fa-solid fa-bars" />
            <input class="add-describe" type="text" placeholder="Thêm mô tả" />
          </div>
        </q-card-section>
        <q-card-actions align="right" class="bg-white text-teal">
          <q-btn class="button-cancel" flat label="Hủy bỏ" v-close-popup />
          <q-btn
            class="button-save_continue"
            flat
            label="Lưu lại và tiếp tục"
          />
          <q-btn class="button-save" flat label="Lưu lại" />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
import { defineComponent, ref } from "vue";

export default defineComponent({
  name: "EmployeeShift",
  components: {},
  setup() {
    const isShowPopupArrangeShift = ref(false);
    const search = ref("");
    const isHover = ref(false);

    const phongBans = ref([
      "Khoa mắt",
      "Khoa cấp cứu",
      "Khoa khám bệnh",
      "Khoa nội tổng hợp",
    ]);
    const caLamViecs = ref([
      "Ca sáng",
      "Ca chiều",
      "Ca tối",
      "Ca ngày",
      "Ca đêm",
      "Nghỉ",
    ]);

    const handleShowPopupArrangeShift = () => {
      isShowPopupArrangeShift.value = true;
    };

    const handleHover = () => {
      isHover.value = !isHover.value;
    };

    return {
      isShowPopupArrangeShift,
      caLamViecs,
      caLamViec: ref(""),
      phongBan: ref(""),
      ngayThang: ref(""),
      phongBans,
      search,
      handleShowPopupArrangeShift,
      isHover,
      handleHover,
    };
  },
});
</script>

<style lang="scss" scoped>
@import "assets/scss/common.scss";
@import "../assest/EmployeeSchedule.scss";
</style>
