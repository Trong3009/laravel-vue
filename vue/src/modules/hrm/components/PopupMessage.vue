<template>
  <q-dialog persistent>
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
        <div class="title-del">Thông báo</div>
        <q-space />
      </q-card-section>

      <q-card-section style="text-align: center">
        <div>{{ message }}</div>
      </q-card-section>
      <q-card-actions align="center" style="margin-top: 26px">
        <q-btn
          style="background: #d9d9d9"
          unelevated
          label="OK"
          v-close-popup
        />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>


<script>
export default {
  name: 'PopupMessage',
}
</script>

<script setup>
import { defineProps } from "vue";
const { message } = defineProps(['message']);
</script>
