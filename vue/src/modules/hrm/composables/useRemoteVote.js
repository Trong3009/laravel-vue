import {api} from "src/boot/axios";
import { BASE_URL_API, config } from "../constants";
import { ref } from "vue";

const apiRemote = `${BASE_URL_API}/remoteVoucher`

const isLoading = ref(false);
const isError = ref(null)
const selected = ref([]);
const rows = ref([]);
const pagination = ref({
    page:1,
    rowsPerpage: 10,
    rowsnumber: 0,
    sortBy: "id",
    descending: 'asc',
});

const filter = ref({
    status:"",
});
const remote = ref({
    id:"",
    date_remote:"",
    start_time:"",
    end_time:"",
    file_remote:"",
    approved_by:"",
    description:"",
    file_image_remote:"",
});

const getListData = async () => {
    isLoading.value = true;
    let sort;
    sort = {};
    sort[pagination.value.sortBy] = pagination.value.descending;
    try {
        const response = await api.get(`${apiRemote }`, {
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
          console.log('rows', rows.value)
        }
      } catch (err) {
        isError.value = err;
      } finally {
        isLoading.value = false;
    }
}
const createRemote = async (data) => {
    if(validateForm()){
        try {
          const response = await api.post(`${apiRemote}`,data, {
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
const updateRemote = async (id, remote) => {
  isLoading.value = true;
  const url = `${apiRemote}/${id}`;
  try{
      const response = await api.put(url , remote,{
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
const repairRemote = async (data) => {
  isLoading.value = true;
  try{
      const response = await api.post(`${apiRemote}`,data,{
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
const deleteDataItem = async (id) => {
  isLoading.value = true;
  try {
    const response = await api.delete(`${apiRemote}/${id}`, {
      ...config,
    });
  } catch (err) {
    isError.value = err;
  } finally {
    isLoading.value = false;
  }
};
const handleFilterByStatus = async () => {
  filter.value.status = "";
  await getListData();
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
const validateForm = () => {
  if(remote.value.date_remote == null){
    return false;
  }
  return true;
};
const useRemoteVote = () => {
  return {
    rows, isError, filter, selected,isLoading, pagination, getListData,createRemote,updateRemote,repairRemote,deleteDataItem, remote,formatStatus,handleFilterByStatus,
  }
};

export {useRemoteVote};