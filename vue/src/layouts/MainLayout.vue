<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar style="background: linear-gradient(to left, rgb(22 110 150), rgb(46 148 197), rgb(147 228 228 / 90%)); padding: 12px">
        <q-btn
          flat
          dense
          round
          @click="toggleLeftDrawer"
          icon="menu"
          aria-label="Menu"
        />
        <q-toolbar-title> </q-toolbar-title>
        <q-btn
          class="q-pa-sm"
          style="background-color: #ffdd57; color: #2747a0"
          label="Đăng xuất"
          no-caps
          @click="logout"
        />
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
      class="bg-white text-black"
    >
      <div style="padding: 5px;">
        <img style="height: 40px; padding-left: 10px" src="../assets/logo/logo-ets2.png" >
      </div>
      <q-list style="color: #236674">
        <q-expansion-item hide-expand-icon>
          <template v-slot:header>
            <q-item-section style="flex: 0 0 40px">
              <img width="40" src="../assets/trang-chu.png" alt="trang-chu" />
            </q-item-section>
            <q-item-section>Trang chủ</q-item-section>
          </template>
        </q-expansion-item>

        <q-separator />
        <q-expansion-item>
          <template v-slot:header>
            <q-item-section style="flex: 0 0 40px">
              <img width="40" src="../assets/employee.png" alt="employee" />
            </q-item-section>
            <q-item-section>Quản lý tài khoản</q-item-section>
          </template>
          <q-list class="q-pl-lg">
            <q-item
              :to="{ name: 'AccountList', params: {} }"
              active-class="q-item-no-link-highlighting"
            >
              <q-item-section avatar>
                <q-icon style="font-size: 20px" name="fa-solid fa-user-group" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Danh sách tài khoản</q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
        </q-expansion-item>

        <q-expansion-item>
          <template v-slot:header>
            <q-item-section style="flex: 0 0 40px">
              <img width="40" src="../assets/mau-bang-luong.png" alt="quanlydanhgia" />
            </q-item-section>
            <q-item-section>Phiếu của tôi</q-item-section>
          </template>
          <q-list class="q-pl-lg">
            <q-item
              :to="{ name: 'VotePauseList', params: {} }"
              active-class="q-item-no-link-highlighting"
            >
              <q-item-section avatar>
                <q-icon style="font-size: 20px" name="fa-solid fa-head-side-cough-slash" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Phiếu xin nghỉ</q-item-label>
              </q-item-section>
            </q-item>
            <q-item
              :to="{ name: 'VoteLateList', params: {} }"
              active-class="q-item-no-link-highlighting"
            >
              <q-item-section avatar>
                <q-icon style="font-size: 20px" name="fa-solid fa-user-clock" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Phiếu đi muộn</q-item-label>
              </q-item-section>
            </q-item>
            <q-item
              :to="{ name: 'VoteOverTime', params: {} }"
              active-class="q-item-no-link-highlighting"
            >
              <q-item-section avatar>
                <q-icon style="font-size: 20px" name="fa-solid fa-head-side-cough-slash" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Phiếu OT</q-item-label>
              </q-item-section>
            </q-item>
            <q-item
              :to="{ name: 'VoteOnsite', params: {} }"
              active-class="q-item-no-link-highlighting"
            >
              <q-item-section avatar>
                <q-icon style="font-size: 20px" name="fa-solid fa-head-side-cough-slash" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Phiếu làm thêm</q-item-label>
              </q-item-section>
            </q-item>
            <q-item
              :to="{ name: 'VoteRemote', params: {} }"
              active-class="q-item-no-link-highlighting"
            >
              <q-item-section avatar>
                <q-icon style="font-size: 20px" name="fa-solid fa-head-side-cough-slash" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Phiếu làm remote</q-item-label>
              </q-item-section>
            </q-item>
            <q-item
              :to="{ name: 'Additional', params: {} }"
              active-class="q-item-no-link-highlighting"
            >
              <q-item-section avatar>
                <q-icon style="font-size: 20px" name="fa-solid fa-head-side-cough-slash" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Bổ sung công</q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
        </q-expansion-item>

        <q-expansion-item>
          <template v-slot:header>
            <q-item-section style="flex: 0 0 40px">
              <img width="40" src="../assets/employee.png" alt="employee" />
            </q-item-section>
            <q-item-section>Quản lý nhân sự</q-item-section>
          </template>
          <q-list class="q-pl-lg">
            <q-item
              :to="{ name: 'PersonnelList', params: {} }"
              active-class="q-item-no-link-highlighting"
            >
              <q-item-section avatar>
                <q-icon style="font-size: 20px" name="fa-solid fa-users" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Danh sách nhân sự</q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
          <q-list class="q-pl-lg">
            <q-item
              :to="{ name: 'ContractList', params: {} }"
              active-class="q-item-no-link-highlighting"
            >
              <q-item-section avatar>
                <q-icon style="font-size: 20px" name="fa-solid fa-file-contract" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Hợp đồng</q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
        </q-expansion-item>

        <q-expansion-item>
          <template v-slot:header>
            <q-item-section style="flex: 0 0 40px">
              <img
                width="40"
                src="../assets/timekeeping.png"
                alt="timekeeping"
              />
            </q-item-section>
            <q-item-section>Quản lý công</q-item-section>
          </template>
          <q-list class="q-pl-lg">
            <q-item
              :to="{ name: 'TimeKeepingList', params: {} }"
              active-class="q-item-no-link-highlighting"
            >
              <q-item-section style="flex: 0 0 40px">
                <img width="40" src="../assets/cham-cong.png" alt="cham-cong" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Chấm công</q-item-label>
              </q-item-section>
            </q-item>

            <q-item
              :to="{ name: 'TimeSheets', params: {} }"
              active-class="q-item-no-link-highlighting"
            >
              <q-item-section style="flex: 0 0 40px">
                <img
                  width="40"
                  src="../assets/tong-hop-bang-cong.png"
                  alt="tong-hop-bang-cong"
                />
              </q-item-section>
              <q-item-section>
                <q-item-label>Tổng hợp bảng công</q-item-label>
              </q-item-section>
            </q-item>

            <q-item
              :to="{ name: 'PermissionManage', params: {} }"
              active-class="q-item-no-link-highlighting"
            >
              <q-item-section style="flex: 0 0 40px">
                <img
                  width="40"
                  src="../assets/quan-ly-phep.png"
                  alt="quan-ly-phep"
                />
              </q-item-section>
              <q-item-section>
                <q-item-label>Quản lý phép</q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
        </q-expansion-item>

        <q-expansion-item>
          <template v-slot:header>
            <q-item-section style="flex: 0 0 40px">
              <img width="40" src="../assets/salary.png" alt="salary" />
            </q-item-section>
            <q-item-section>Quản lý lương</q-item-section>
          </template>
          <q-list class="q-pl-lg">
            <q-item
              :to="{ name: 'ComponentSalary', params: {} }"
              active-class="q-item-no-link-highlighting"
            >
              <q-item-section style="flex: 0 0 40px">
                <img
                  width="40"
                  src="../assets/thanh-phan-luong.png"
                  alt="thanh-phan-luong"
                />
              </q-item-section>
              <q-item-section>
                <q-item-label>Thành phần lương</q-item-label>
              </q-item-section>
            </q-item>

            <q-item
              :to="{ name: 'SalarySheet', params: {} }"
              active-class="q-item-no-link-highlighting"
            >
              <q-item-section style="flex: 0 0 40px">
                <img
                  width="40"
                  src="../assets/mau-bang-luong.png"
                  alt="mau-bang-luong"
                />
              </q-item-section>
              <q-item-section>
                <q-item-label>Mẫu bảng lương</q-item-label>
              </q-item-section>
            </q-item>

            <q-item
              :to="{ name: 'EmployeePayroll', params: {} }"
              active-class="q-item-no-link-highlighting"
            >
              <q-item-section style="flex: 0 0 40px">
                <img
                  width="40"
                  src="../assets/tinh-luong.png"
                  alt="tinh-luong"
                />
              </q-item-section>
              <q-item-section>
                <q-item-label>Tính lương</q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
        </q-expansion-item>

        <q-expansion-item hide-expand-icon>
          <template v-slot:header>
            <q-item-section style="flex: 0 0 40px">
              <img
                width="40"
                src="../assets/quan-ly-de-nghi.png"
                alt="quan-ly-de-nghi"
              />
            </q-item-section>
            <q-item-section>Quản lý đề nghị</q-item-section>
          </template>
        </q-expansion-item>

        <q-expansion-item hide-expand-icon>
          <template v-slot:header>
            <q-item-section style="flex: 0 0 40px">
              <img
                width="40"
                src="../assets/quan-ly-danh-gia.png"
                alt="quan-ly-danh-gia"
              />
            </q-item-section>
            <q-item-section>Quản lý đánh giá</q-item-section>
          </template>
        </q-expansion-item>

        <q-expansion-item hide-expand-icon>
          <template v-slot:header>
            <q-item-section style="flex: 0 0 40px">
              <img
                width="40"
                src="../assets/bao-cao-thong-ke.png"
                alt="bao-cao-thong-ke"
              />
            </q-item-section>
            <q-item-section>Báo cáo thông kê</q-item-section>
          </template>
        </q-expansion-item>

        <q-expansion-item v-if="hasPermission('role-list')">
          <template v-slot:header>
            <q-item-section style="flex: 0 0 40px">
              <img width="40" src="../assets/cai-dat.png" alt="cai-dat" />
            </q-item-section>
            <q-item-section>Cài đặt hệ thống</q-item-section>
          </template>
          <q-list class="q-pl-lg">
            <q-item
              :to="{ name: 'RoleList', params: {} }"
              active-class="q-item-no-link-highlighting"
            >
              <q-item-section avatar>
                <q-icon style="font-size: 20px" name="fa-solid fa-gears" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Danh sách vai trò</q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
        </q-expansion-item>

        <q-separator />

        <q-expansion-item
          hide-expand-icon style="margin-top: 100px"
          @click="logout"
        >
          <template v-slot:header>
            <q-item-section style="flex: 0 0 40px">
              <img width="40" src="../assets/dang-xuat.png" alt="dang-xuat" />
            </q-item-section>
            <q-item-section>Đăng xuất</q-item-section>
          </template>
        </q-expansion-item>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import EssentialLink from "components/EssentialLink.vue";
import Messages from "./Messages.vue";
import {api} from "boot/axios";
import {clearAuthToken, config, BASE_URL_API, hasPermission} from "src/modules/hrm/constants";
import { defineComponent, ref } from "vue";
import { useQuasar } from "quasar";
import router from "src/router";

export default defineComponent({
  name: "MainLayout",
  components: {
    EssentialLink,
    Messages,
  },

  setup() {
    const leftDrawerOpen = ref(false);
    const $q = useQuasar();
    const logout = async () => {
      try {
        const response = await api.post(`${BASE_URL_API}/logout`, null, {...config});
        clearAuthToken();
        await router.push('/login');
      } catch (error) {
        console.error('Logout failed:', error);
        clearAuthToken();
        await router.push('/login');
      }
    };

    return {
      $q,
      leftDrawerOpen,
      toggleLeftDrawer() {
        leftDrawerOpen.value = !leftDrawerOpen.value;
      },
      logout,
      hasPermission,
    };
  },
});
</script>

<style lang="scss">
@import "../assets/scss/common.scss";
/* FONT AWESOME GENERIC BEAT */
.fa-beat {
  animation: fa-beat 5s ease infinite;
}

@keyframes fa-beat {
  0% {
    transform: scale(1);
  }
  5% {
    transform: scale(1.25);
  }
  20% {
    transform: scale(1);
  }
  30% {
    transform: scale(1);
  }
  35% {
    transform: scale(1.25);
  }
  50% {
    transform: scale(1);
  }
  55% {
    transform: scale(1.25);
  }
  70% {
    transform: scale(1);
  }
}
</style>
