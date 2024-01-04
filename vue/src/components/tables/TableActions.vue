<template>
  <q-card class="no-shadow" bordered>
    <q-card-section>
      <div class="text-h6 text-grey-8">
        Products
        <q-btn class="float-right text-capitalize  shadow-3" label="Create" color="primary" @click="prompt = true" />
        <div class="float-right q-pa-md q-gutter-sm">
          <q-dialog v-model="prompt" persistent>
            <q-card style="min-width: 750px">
              <q-card-section>
                <div class="text-h6">Create product</div>
              </q-card-section>
              <q-form @submit="onSubmit" @reset="onReset" class="q-gutter-md">
                <q-card-section class="q-pt-none">
                  <q-input filled v-model="product.name" label="Product name *" lazy-rules
                    :rules="[val => val && val.length > 0 || 'Please type something']" />

                  <q-input filled type="number" v-model="product.code" label="Code *" lazy-rules :rules="[
                    val => val !== null && val !== '' || 'Please type code',
                    val => val > 9999 && val < 100000 || 'Please type a real code']" />

                  <q-input filled type="number" v-model="product.price" label="Price *" lazy-rules :rules="[
                    val => val !== null && val !== '' || 'Please type your price',
                    val => val > 0 && val < 100 || 'Please type a real price'
                  ]" />

                </q-card-section>

                <q-card-actions align="right" class="text-primary">
                  <q-btn label="Cancel" type="reset" color="primary" flat class="q-ml-sm" v-close-popup />
                  <q-btn label="Submit" type="submit" color="primary" />
                </q-card-actions>
              </q-form>
            </q-card>
          </q-dialog>
        </div>
      </div>
    </q-card-section>
    <q-separator></q-separator>
    <q-card-section class="q-pa-md">
      <q-table v-model:pagination.sync="pagination" :rows="items" :columns="columns" class="no-shadow"
        @request="handleRequest">
        <template v-slot:body-cell-Name="props">
          <q-td :props="props">
            <q-item style="max-width: 420px">
              <q-item-section avatar>
                <q-avatar>
                  <img :src="props.row.avatar">
                </q-avatar>
              </q-item-section>

              <q-item-section>
                <q-item-label>{{ props.row.name }}</q-item-label>
              </q-item-section>
            </q-item>
          </q-td>
        </template>
        <template v-slot:body-cell-Action="props">
          <q-td :props="props">
            <q-btn icon="edit" size="sm" flat dense />
            <q-btn icon="delete" size="sm" class="q-ml-sm" flat dense />
          </q-td>
        </template>
      </q-table>
    </q-card-section>
  </q-card>
</template>

<script>
import { number } from 'echarts';
import { defineComponent, onMounted, ref } from 'vue'
import axios from 'axios';
import { data } from 'autoprefixer';
import BaseComponent from 'src/components/BaseComponents';

export default ({
  name: "product",
  extends: BaseComponent,
  data() {
    return {
      model: 'product',
    }
  },


  setup() {
    const products = ref([]);

    const pagination = ref({
      sortBy: 'name',
      descending: false,
      page: 1,
      rowsPerPage: 8,
      rowsNumber: 0
    });

    const columns = [
      { name: 'id', align: 'left', label: 'Id', field: 'id', sortable: true },
      { name: 'name', align: 'left', label: 'Name', field: 'name', sortable: true },
      { name: 'code', align: 'left', label: 'Code', field: 'code' },
      { name: 'description', align: 'left', label: 'Description', field: 'description' },
      { name: 'price', align: 'left', label: 'Price', field: 'price' },
      { name: 'Action', label: '', field: 'Action', sortable: false, align: 'center' },
    ];

    // const fetchProducts = async (page = 0) => {
    //   const res = await axios.get('http://18.143.148.76/api/admin/product', {
    //     params: { page: page }
    //   });
    //   const data = res.data.data;
    //   products.value = data.data;
    //   pagination.value.page = data.current_page;
    //   pagination.value.rowsPerPage = data.per_page;
    //   pagination.value.rowsNumber = data.total;
    // }
    // const handleRequest = (props) => {
    //   this.getItems(props.pagination.page);
    // }
    // onMounted(() => {
    //   this.getItems();
    // })


    const product = ref({
      name: null,
      code: null,
      description: null,
      price: null
    })



    return {
      prompt: ref(false),
      // handleRequest,
      columns,
      products,
      pagination,
      product,
      onSubmit() {

      },

      onReset() {
        product.value.name = null,
          product.value.price = null
      }
    }
  }
})
</script>

<style scoped></style>
