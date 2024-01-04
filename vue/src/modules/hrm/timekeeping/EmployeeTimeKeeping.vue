<template>
  <div class="block-info">
    <div class="q-container">
      <div class="row block-info__tabs">
        <div
          v-for="(tab, ind) in tabs"
          :key="`tab-${ind}`"
          @click="changeTab(tab.component)"
          :class="`tab ${isComponent === tab.component ? 'active-tab' : ''}`"
        >
          {{ tab.name }}
        </div>
      </div>
      <component :is="isComponent" />
    </div>
  </div>
</template>

<script>
import { defineComponent, ref } from "vue";
import EmployeeCardSwipeData from "./components/EmployeeCardSwipeData.vue";
import EmployeeSchedule from "./components/EmployeeSchedule.vue";
import EmployeeShift from "./components/EmployeeShift.vue";

export default defineComponent({
  name: "EmployeeTimeKeeping",
  setup() {
    const isComponent = ref(EmployeeCardSwipeData);

    const tabs = ref([
      {
        name: "Dữ liệu quẹt thẻ",
        component: EmployeeCardSwipeData,
      },
      {
        name: "Ca làm việc",
        component: EmployeeShift,
      },
      {
        name: "Lịch làm việc",
        component: EmployeeSchedule,
      },
    ]);

    const changeTab = (component) => {
      isComponent.value = component;
    };

    return {
      tabs,
      isComponent,
      changeTab,
    };
  },
});
</script>

<style lang="scss" scoped>
@import "./assest/EmployeeTimeKeeping.scss";
</style>
