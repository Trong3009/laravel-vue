<template>
  <div class="notify">
    <div class="notify__icon"><q-icon name="fa-solid fa-circle-check" /></div>
    <div class="notify__text">Tải lên danh sách xe thành công</div>
    <div class="notify__icon-cancel"><q-icon name="fa-solid fa-xmark" v-close-popup /></div>
  </div>
  <div class="notify" style="background: #D65745;" >
    <div class="notify__icon"><q-icon name="fa-solid fa-circle-exclamation" /></div>
    <div class="notify__text">Tải lên danh sách xe Không thành công. Vui lòng thử lại</div>
    <div class="notify__icon-cancel"><q-icon name="fa-solid fa-xmark" v-close-popup /></div>
  </div>

</template>

<script>
export default {

}
</script>

<style lang="scss">
@import '../assets/scss/NotifycationPopup';
</style>
