<template>
  <q-card class="no-shadow" bordered>
    <q-card-section>
      <div class="text-h6 text-grey-8">
        Product
      </div>
    </q-card-section>
    <q-separator></q-separator>
    <q-card-section class="q-pa-none">
      <q-table square class="no-shadow" title="Products" :rows="data.data" :columns="columns" row-key="name"
        :filter="filter">
        <template v-slot:top-right>
          <q-input v-if="show_filter" filled borderless dense debounce="300" v-model="filter" placeholder="Search">
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>

          <q-btn class="q-ml-sm" icon="filter_list" @click="show_filter = !show_filter" flat />
        </template>
      </q-table>
      <template>
        <div class="q-pa-lg">
          <div class="q-gutter-md">
            <q-pagination v-model="data.current_page" max="5" direction-links flat color="grey" active-color="primary" />
          </div>
        </div>
      </template>
    </q-card-section>
  </q-card>
</template>

<script>
import { defineComponent, ref } from 'vue'


const columns = [
  { name: 'id', align: 'left', label: 'Id', field: 'id', sortable: true },
  { name: 'name', align: 'left', label: 'Name', field: 'name', sortable: true },
  { name: 'code', align: 'left', label: 'Code', field: 'code' },
  { name: 'description', align: 'left', label: 'Description', field: 'description' },
  { name: 'price', align: 'left', label: 'Price', field: 'price' },
];

export default defineComponent({
  name: "TableBasic",
  props: {
    data: Object
  },
  setup() {
    const show_filter = ref(false)

    return {
      filter: ref(''),
      show_filter,
      columns
    }
  }
})
</script>

<style scoped></style>
