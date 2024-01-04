<template>
  <router-view />
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
  name: "App",
});
</script>

<style lang="scss">
@import "src/assets/scss/common.scss";
@import "src/assets/scss/reset.scss";
@import url("https://fonts.googleapis.com/css2?family=Roboto&display=swap");
</style>
