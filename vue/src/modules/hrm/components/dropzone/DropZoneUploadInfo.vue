<template>
  <div @drop.prevent="drop" @change="selectedFile">
    <div
      @dragenter.prevent="toggleActive"
      @dragleave.prevent="toggleActive"
      @dragover.prevent
      @drop.prevent="toggleActive"
      :class="{ 'active-dropzone': active }"
      class="dropzone"
      for="dropzoneFile"
    >
      <div class="dropzone-block">
        <div class="dropzone-block__img">
          <img
            src="../../assets/images/btn-dropzone.png"
            alt="button dropzone"
          />
        </div>
        <div class="dropzone-block__content">
          <label for="dropzoneFileUpload"
            >Click hoặc kéo thả để tải lên file</label
          >
          <span>Hỗ trợ định dạng xls, xlsx</span>
        </div>
      </div>
      <input
        type="file"
        id="dropzoneFileUpload"
        accept=".xls, .xlsx"
        class="dropzoneFileUpload"
      />
    </div>
  </div>
</template>

<script>
import { ref } from "vue";
export default {
  name: "DropZoneUploadInfo",
  setup(props, { emit }) {
    const active = ref(false);

    const toggleActive = () => {
      active.value = !active.value;
    };

    const drop = (e) => {
      const file = e.dataTransfer.files[0];
      emit("dropzone-file", file);
    };

    const selectedFile = () => {
      const file = document.querySelector("#dropzoneFileUpload").files[0];
      emit("dropzone-file", file);
    };

    return {
      active,
      toggleActive,
      drop,
      selectedFile,
    };
  },
};
</script>

<style scoped lang="scss">
@import "../../assets/scss/dropzone/DropZoneUploadInfo";
</style>
