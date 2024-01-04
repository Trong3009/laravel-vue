<template>
  <q-page class="q-pa-sm">
    <tables-actions></tables-actions>

  </q-page>
</template>

<script>
import { defineComponent, defineAsyncComponent, ref } from 'vue';

const data = [];

export default defineComponent({
  name: "Tables",
  components: {
    TablesActions: defineAsyncComponent(() => import('components/tables/TableActions.vue'))
  },
})
</script>

<style></style>
