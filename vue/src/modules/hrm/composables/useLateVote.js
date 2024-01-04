import { api } from "src/boot/axios";
import { BASE_URL_API, token, config } from "src/modules/hrm/constants";
import { ref } from "vue";
const apiLateVote = `${BASE_URL_API}/lateVoucher`;

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
  status:"",
})
const latevote = ref({
  late_date:"",
  shift_job:"",
  name: "",
  since: "",
  todate: "",
  number_minute: "",
  reason: "",
  object: "",
  active: "",
  start_time: "",
  end_time: "",
  number_minute: 0,
  approved_by:"",
});
const getListData = async () => {
  isLoading.value = true;
  let sort;
  sort = {};
  sort[pagination.value.sortBy] = pagination.value.descending;
  try {
    const response = await api.get(`${apiLateVote}`, {
      ...config,
      params: {
        page: pagination.value.page,
        per_page: pagination.value.rowsPerPage,
        filter: {...filter.value},
        sort: {...sort},
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

const createLateVote = async (data) => {
  if (validateForm()) {
    isLoading.value = true;
    try {
      const response = await api.post(`${apiLateVote}`, data, {
        ...config,
      });
      if (response.data.code === 200) {
        await getListData(pagination.value.page, pagination.value.rowsPerPage);
      } else {
        console.log('error', response.data.error)
      }
      alert('tạo thành công')
    } catch (err) {
      isError.value = err;
    } finally {
      isLoading.value = false;
    }
  } else {
    alert('Vui lòng nhập đầy đủ')
  }
};
const updateItem = async (id, latevote) => {
  isLoading.value = true;
  try {
    const response = await api.put(`${apiLateVote}/${id}`, latevote,{
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
const deleteDataItem = async (id) => {
  isLoading.value = true;
  try {
    const response = await api.delete(`${apiLateVote}/${id}`, {
      ...config,
    });
  } catch (err) {
    isError.value = err;
  } finally {
    isLoading.value = false;
  }
};
const formatStatus = (val) => {
  switch (val) {
    case 1:
      return "Đã duyệt";
    case 2:
      return "Đang chờ";
    case 3:
      return "Dã hủy";
    default:
      return "Vui lòng chọn";
  }
};
//List function filter

const handleFilterByLateVote = async (val) => {
  filter.value.status = val;
  await getListData();
}
const handleFilterByPosition = async (val) => {
  filter.value.position = val;
  await getListData();
}
const validateForm = () => {
  if (!latevote.value.reason.trim()) {
    return false;
  }
  return true;
};
const useLateVote = () => {
  return {
    rows,
    isError,
    filter,
    selected,
    isLoading,
    pagination,
    getListData,
    updateItem,
    createLateVote,
    deleteDataItem,
    apiLateVote,
    handleFilterByLateVote,
    handleFilterByPosition,
    latevote,
    formatStatus
  };
};

export { useLateVote };
