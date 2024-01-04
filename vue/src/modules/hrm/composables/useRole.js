import { api } from "boot/axios";
import { BASE_URL_API, config } from "src/modules/hrm/constants";
import { ref } from "vue";
import router from "src/router";
const apiRole = `${BASE_URL_API}/roles`;

const isLoading = ref(false);
const isError = ref(null);
const selected = ref([]);
const rows = ref([]);
const pagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 0,
  sortBy: "id",
  descending: "asc",
});

const filter = ref({
  position: null,
  job_title: null,
  keyword: "",
});

const role = ref({
  id: "",
  name: "",
  description: "",
  permission_id: [],
});
const getListData = async () => {
  isLoading.value = true;
  try {
    const response = await api.get(`${apiRole}`, {
      ...config,
      params: {
        page: pagination.value.page,
        per_page: pagination.value.rowsPerPage,
        filter: {...filter.value},
        sort: {
          [pagination.value.sortBy]: pagination.value.descending ? 'desc' : 'asc',
        },
      },
    });
    if (response.data.code === 200) {
      rows.value = response.data.data.data;
      pagination.value.page = response.data.data.current_page;
      pagination.value.rowsPerPage = response.data.data.per_page;
      pagination.value.rowsNumber = response.data.data.total;
    }
  } catch (err) {
    console.log('error', err);
  }
};

const onRequest = (props) => {
  const { page, rowsPerPage, sortBy, descending } = props.pagination;
  pagination.value.page = page;
  pagination.value.rowsPerPage = rowsPerPage;
  pagination.value.sortBy = sortBy;
  pagination.value.descending = descending;
  getListData();
};


const createRole = async (data) => {
  isLoading.value = true;
  try {
    const response = await api.post(`${apiRole}`, data,  {
      ...config,
    });
    if (response.data.code === 200) {
      await router.push({name: 'RoleList'});
    } else {
      console.log('error', response.data.error)
    }
  } catch (err) {
    console.log('error', err);
  }
};

const deleteDataItem = async (id) => {
  isLoading.value = true;
  try {
    const response = await api.delete(`${apiRole}/${id}`, {
      ...config,
    });
  } catch (err) {
    console.log('error', err);
  }
};
const updateItem = async (id, role) => {
  isLoading.value = true;
  try {
    const response = await api.put(`${apiRole}/${id}`, role,{
      ...config,
    });
    if (response.data.code === 200) {
      await router.push({name: 'RoleList'});
    }
  } catch (err) {
    console.log('error', err);
  }
};

//List function filter
const handleDeleteKeyWord = async () => {
  filter.value.keyword = "";
  await getListData();
};

const handleFilterByJobTitle = async (val) => {
  filter.value.job_title = val;
  await getListData();
}
const handleFilterByPosition = async (val) => {
  filter.value.position = val;
  await getListData();
}

const useRole = () => {
  return {
    rows,
    isError,
    filter,
    selected,
    isLoading,
    pagination,
    getListData,
    deleteDataItem,
    updateItem,
    createRole,
    apiRole,
    handleDeleteKeyWord,
    handleFilterByJobTitle,
    handleFilterByPosition,
    role,
    onRequest
  };
};

export { useRole };
