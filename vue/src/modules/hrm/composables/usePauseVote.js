import { api } from "src/boot/axios";
import { BASE_URL_API, token, config } from "../constants";
import { ref } from "vue";
import { date } from "quasar";

const apiPauseVote = `${BASE_URL_API}/pauseVoucher`;
const isLoading = ref(false);
const isError = ref(null);
const selected = ref([]);
const rows = ref([]);
const pagination = ref({
    page: 1,
    rowsPerpage: 10,
    rowsNumber:0,
    sortBy: "id",
    descending: false,
});
const filter = ref({
  status: ""
});
const pausevote = ref({
  since_date:"",
  since_session:"",
  todate_session:"",
  todate_date:"",
  types_break: "",
  reason: "",
});
const getListData = async () => {
    isLoading.value =true;
    try{
        const response = await api.get(`${apiPauseVote}`,{
            ...config,
            params:{
                page: pagination.value.page,
                per_page: pagination.value.rowsPerpage,
                filter: {...filter.value},
                sort: {
                  [pagination.value.sortBy]: pagination.value.descending ? 'desc' : 'asc',
                },
            }
        });
        if(response.data.code === 200){
            rows.value = response.data.data.data;
            pagination.value.page = response.data.data.current_page;
            pagination.value.rowsPerpage = response.data.data.per_page;
            pagination.value.rowsNumber = response.data.data.total;
            console.log('rows', rows.value)
        }
    }catch(err){
        isError.value = err;
    }finally{
        isLoading.value = false;
    }
};
const createPauseVote = async (data) => {
    if (validateForm()) {
        try {
          const response = await api.post(`${apiPauseVote}`, data,  {
            ...config,
          });
          if (response.data.code === 200) {
            await getListData(pagination.value.page, pagination.value.rowsPerpage);
          } else {
            console.log('error', response.data.error)
          }
        } catch (err) {
          isError.value = err;
        }
      } else {
        alert('Vui lòng nhập đủ thông tin')
      }
};

const updatePauseVote = async (id,) => {
    isLoading.value = true;
    try{
        const response = await api.put(`${apiPauseVote}/${id}`,{
            ...config,
        });
        if (response.data.code === 200) {
            await getListData();
          }
    }catch(err){
        if(err.response.status === 422){
          const errors = err.response.data.errors
          console.log(errors);
        }else{
          isError.value =err;
        }
    }finally{
        isLoading.value =false;
    }
};
const deleteDataItem = async (id) => {
  isLoading.value = true;
  try {
    const response = await api.delete(`${apiPauseVote}/${id}`, {
      ...config,
    });
  } catch (err) {
    isError.value = err;
  } finally {
    isLoading.value = false;
  }
};
const isRequired = () => {
  return [(val) => !!val || "Không nên để trống"]
}
const formatDate = (val) => {
    return date.formatDate(val, 'DD/MM/YYYY')
}
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
  const handleFilterByStatus = async () => {
   filter.value.status = "";
   await getListData();
  };
  const validateForm = () => {
    if (!pausevote.value.reason.trim()) {
      return false;
    }
    return true;
  };
const usePauseVote = () =>{
    return{
        rows,
        isError,
        filter,
        selected,
        isLoading,
        pagination,
        getListData,
        createPauseVote,
        updatePauseVote,
        apiPauseVote,
        pausevote,
        formatDate,
        formatStatus,
        handleFilterByStatus,
        deleteDataItem
    };
};
export { usePauseVote };