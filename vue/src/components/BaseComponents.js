import { defineComponent } from 'vue'
import axios from 'axios';

export default defineComponent({
  name: 'BaseComponents',
  data() {
    return {
      model: '',
      items: [],
      pagination: {
        sortBy: 'name',
        descending: false,
        page: 1,
        rowsPerPage: 8,
        rowsNumber: 0
      },
      form: {},
      selectedItems: [],
      loading: false,
      fieldFilter: {},
      handleRequest
    }
  },

  mounted() {
    console.log(1);
    this.getItems(1);
    handleRequest = (props) => {
      getItems(props.pagination.page);
    }
  },

  methods: {
    async getItems(page) {
      console.log(2);
      const res = await axios.get(`http://18.143.148.76/api/admin/${this.model}`, {
        params: { page: page }
      });
      const data = res.data.data;
      this.items = data.data;
      this.pagination.page = data.current_page;
      this.pagination.rowsPerPage = data.per_page;
      this.pagination.rowsNumber = data.total;

    }
  }

})