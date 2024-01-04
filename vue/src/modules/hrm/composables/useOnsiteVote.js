import { api } from "src/boot/axios";
import { BASE_URL_API, token, config } from "../constants";
import { ref } from "vue";
import { data } from "autoprefixer";
const apiOnsiteVote = `${BASE_URL_API}/onsiteVoucher`;
const isLoading = ref(false);
const isError = ref(null);
const selected = ref([]);
const rows = ref([]);
const pagination = ref({
    page: 1,
    rowsPerpage: 10,
    rowsNumber:0,
    sortBy: "id",
    descending: "asc",
});
const filter = ref({
    status: ""
});
const onsite = ref({
    id:"",
    date_onsite:"",
    start_time:"",
    end_time:"",
    location:"",
    object:"",
    description:"",
    file_onsite:"",
    file_image_file:"",
});
const getListData = async () => {
    let sort;
    sort = {};
    sort[pagination.value.sortBy] = pagination.value.descending;
    try{
        const response = await api.get(`${apiOnsiteVote}`,{
            ...config,
            params:{
                page: pagination.value.page,
                per_page: pagination.value.rowsPerpage,
                filter: {...filter.value},
                sort:{...sort}
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
const createOnsiteVote = async (data) => {
    if (validateForm()){
        try{
            const response = await api.post(`${apiOnsiteVote}`,data,{
                ...config,
            });
            if(response.data.code === 200){
                await getListData(pagination.value.page, pagination.value.rowsPerpage)
            } else{
                console.log('error', response.data.error)
            }
        }catch (err){
            isError.value = err
        }
    }else {
        alert('Nhập thiếu')
    }
};
const updateOnsite = async (id, onsite) => {
    isLoading.value = true;
    try{
        const response = await api.put(`${apiOnsiteVote}/${id}` , onsite,{
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
      const response = await api.delete(`${apiOnsiteVote}/${id}`, {
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
const handleFilterByStatus = async () => {
    filter.value.status = "";
    await getListData();
   };
const validateForm = () => {
     if (!onsite.value.location.trim()) {
       return false;
     }
     return true;
   };
const useOnsiteVote = () => {
    return {
        rows, isError,isLoading,selected,pagination,filter,getListData,createOnsiteVote,updateOnsite,deleteDataItem,onsite,formatStatus,handleFilterByStatus,
    }
};

export { useOnsiteVote };