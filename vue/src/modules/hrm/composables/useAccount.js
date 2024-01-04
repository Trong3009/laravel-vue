import { api } from "boot/axios";
import { BASE_URL_API, token, config } from "src/modules/hrm/constants";
import { ref } from "vue";
const apiAccount = `${BASE_URL_API}/users`;

const isLoading = ref(false);
const isPopupSelected = ref(false);
const isPopupDeleteItems = ref(false);
const isError = ref(null);
const selected = ref([]);
const rows = ref([]);
const pagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 0,
  sortBy: "id",
  descending: false,
});

const filter = ref({
  role_id: null,
  department_id: null,
  keyword: "",
});

const account = ref({
  id: "",
  parent_id: "",
  user_detail_id: "",
  role_id: "",
  is_active: "",
  user_name: "",
  password: "",
});

const getListData = async () => {
  isLoading.value = true;
  try {
    const response = await api.get(apiAccount, {
      ...config,
      params: {
        page: pagination.value.page,
        per_page: pagination.value.rowsPerPage,
        filter: { ...filter.value },
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
    isError.value = err;
  } finally {
    isLoading.value = false;
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

const createAccount = async (data) => {
  isLoading.value = true;
  try {
    const response = await api.post(`${apiAccount}`, data,  {
      ...config,
    });
    if (response.data.code === 200) {
      await getListData();
    } else {
      console.log('error', response.data.error)
    }
  } catch (err) {
    isError.value = err;
  } finally {
    isLoading.value = false;
  }
};

const deleteDataItem = async (id) => {
  isLoading.value = true;
  try {
    const response = await api.delete(`${apiAccount}/${id}`, {
      ...config,
    });
    if (response.data.code === 200) {
      await getListData();
    }
  } catch (err) {
    isError.value = err;
  } finally {
    isLoading.value = false;
  }
};
const updateItem = async (id, data) => {
  isLoading.value = true;
  try {
    const response = await api.put(`${apiAccount}/${id}`, data,{
      ...config,
    });
    if (response.data.code === 200) {
      await getListData();
    }
  } catch (err) {
    isError.value = err;
  } finally {
    isLoading.value = false;
  }
};

const deleteMultiItem = async (ids) => {
  try {
    const response = await api.post(`${apiAccount}/delete`,
      {
        'ids': ids,
      },
      {
        ...config,
      });
    if (response.data.code === 200) {
      await getListData();
      isPopupDeleteItems.value = false;
      selected.value = [];
    }
  } catch (err) {
    console.log('error', err);
  }
}
const lockAndUnlockAccount = async (id, data) => {
  const response = await api.put(`${apiAccount}/lock-and-unlock/${id}`, data, {...config});

  return response;
}
//List function filter
const handleDeleteKeyWord = async () => {
  filter.value.keyword = "";
  await getListData();
};

const handleFilterByPosition = async (val) => {
  filter.value.position = val;
  await getListData();
}

const useAccount = () => {
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
    createAccount,
    apiAccount,
    handleDeleteKeyWord,
    handleFilterByPosition,
    account,
    isPopupSelected,
    deleteMultiItem,
    isPopupDeleteItems,
    lockAndUnlockAccount,
    onRequest
  };
};

export { useAccount };
