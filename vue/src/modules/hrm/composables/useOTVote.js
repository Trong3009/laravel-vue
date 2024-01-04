import { api } from "src/boot/axios";
import { BASE_URL_API, token ,config } from "../constants";
import { ref } from "vue";
const apiOverTimeVote = `${BASE_URL_API}/otVoucher`;

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
    active:"",
  })
  
  const otvote = ref({
    date_ot: "",
    date_type: "",
    start_time: "",
    end_time: "",
    total_time: "",
    shift: "",
    client:"",
    object: "",
    multi_time: "",
    total_multi:"",
    description: "",
    approved_by_user: "",
    description:"",
    file_overtime:"",
  });
  
const getListData = async () => {
    isLoading.value = true;
    let sort;
    sort = {};
    sort[pagination.value.sortBy] = pagination.value.descending;
    try {
      const response = await api.get(`${apiOverTimeVote}`, {
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
  const createOvertime = async (data) => {
    if(validateForm()){
      try {
        const response = await api.post(`${apiOverTimeVote}`,data, {
          ...config,
        });
        if (response.data.code === 200){
          await getListData(pagination.value.page, pagination.value.rowsPerPage);
        }else{
          console.log('error', response.data.console.error)
        }
      }catch (err){
        isError.value = err;
      }
    } else {
      alert('Vui lòng nhập đầy đủ')
    }
  }

  const repairOverTime = async (data) => {
    isLoading.value = true;
    try{
        const response = await api.post(`${apiOverTimeVote}`,data,{
            ...config,
        });
        if (response.data.code === 200) {
            await getListData();
            alert('saddsa')
          }
    }catch(err){
        isError.value =err;
    }finally{
        isLoading.value =false;
    }
};
const updateOverTimeVote = async (id, otvote) => {
  isLoading.value = true;
  try{
      const response = await api.put(`${apiOverTimeVote}/${id}` , otvote,{
          ...config,
      });
      if (response.data.code === 200) {
          await getListData();
        }
  }catch(err){
      isError.value =err;
  }finally{
      isLoading.value =false;
  }
};
const deleteDataItem = async (id) => {
  isLoading.value = true;
  try {
    const response = await api.delete(`${apiOverTimeVote}/${id}`, {
      ...config,
    });
  } catch (err) {
    isError.value = err;
  } finally {
    isLoading.value = false;
  }
};
  const handleFilterByOTVote = async (val) => {
    filter.value.status = val;
    await getListData();
  }

  const handleFilterByPosition = async (val) => {
    filter.value.position = val;
    await getListData();
  }
  const validateForm = () => {
    if (!otvote.value.object.trim()) {
      return false;
    }
    return true;
  }; 
  const useOTVote = () => {
    return{
        rows,
        isError,
        isLoading,
        pagination,
        selected,
        getListData,
        apiOverTimeVote,
        handleFilterByOTVote,
        handleFilterByPosition,
        createOvertime,
        repairOverTime,
        updateOverTimeVote,
        deleteDataItem,
        otvote
    }
  };

  export { useOTVote };