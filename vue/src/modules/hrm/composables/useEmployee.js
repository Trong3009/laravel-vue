import { api } from "boot/axios";
import { BASE_URL_API, config } from "src/modules/hrm/constants";
import { ref } from "vue";
import router from "src/router";
const apiEmployee = `${BASE_URL_API}/userDetails`;
const isLoading = ref(false);
const isError = ref(null);
const isPopupCreate = ref(false);
const isPopupSelected = ref(false);
const isPopupDeleteItems = ref(false);
const isPopupUpload = ref(false);
const isPopupMessage = ref(false);
const message = ref("");
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
  work_status: null,
  keyword: "",
});

const employee = ref({
  id: "",
  code: "",
  name: "",
  email: "",
  phone: "",
  birthday: "",
  avatar: "",
  gender: "",
  permanent_address: "",
  residence_address: "",
  domicile: "",
  place_of_birth: "",
  nationality: "",
  nation: "",
  religion: "",
  marital_status: "",
  name_father: "",
  name_mother: "",
  name_wife_husband: "",
  birthday_father: "",
  birthday_mother: "",
  birthday_wife_husband: "",
  note_user: "",
  person_contact: "",
  person_address: "",
  person_phone: "",
  person_email: "",
  vehicle_type: "",
  vehicle_name: "",
  vehicle_number: "",
  type_of_documents: "",
  identity_card: "",
  date_identity_card: "",
  address_identity_card: "",
  is_member_communist: "",
  number_member_communist: "",
  date_communist: "",
  address_communist: "",
  health_condition: "",
  height: "",
  weight: "",
  note_health_condition: "",
  bank_number: "",
  bank_name: "",
  bank_branch: "",
  bank_account: "",
  transfer_level: "",
  training_units: "",
  specialize: "",
  probation_day: "",
  official_day: "",
  position: "",
  job_title: "",
  work_note: "",
  work_status: "",
  quit_reason: "",
  quit_date: "",
  basic_salary: "",
  responsibility_salary: "",
  meal_allowance: "",
  travel_allowance: "",
  insurance_salary: "",
  insurance_amount: "",
});
const getListData = async () => {
  isLoading.value = true;
  try {
    const response = await api.get(`${apiEmployee}`, {
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

const createEmployee = async (data) => {
  if (validateForm()) {
    try {
      const response = await api.post(`${apiEmployee}`, data,  {
        ...config,
      });
      if (response.data.code === 200) {
        await getListData();
        isPopupCreate.value = false;
      } else {
        console.log('error', response.data.error)
      }
    } catch (err) {
      isError.value = err;
    }
  } else {
    alert('Vui lòng nhập đủ thông tin bắt buộc')
  }
};

const deleteDataItem = async (id) => {
  isLoading.value = true;
  try {
    const response = await api.delete(`${apiEmployee}/${id}`, {
      ...config,
    });
  } catch (err) {
    isError.value = err;
  }
};

const deleteMultiItem = async (ids) => {
  try {
    const response = await api.post(`${apiEmployee}/delete`,
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
};
const updateItem = async (id, employee) => {
  isLoading.value = true;
  try {
    const response = await api.put(`${apiEmployee}/${id}`, employee,{
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

//List function filter
const handleDeleteKeyWord = async () => {
  filter.value.keyword = "";
  await getListData();
};

const handleFilterByWorkStatus = async (val) => {
  filter.value.work_status = val;
  await getListData();
}
const handleFilterByPosition = async (val) => {
  filter.value.position = val;
  console.log('filter.value.position', filter.value.position);
  await getListData();
}

//TO DO
const validateForm = () => {
  if (!employee.value.name.trim() || !employee.value.email.trim() || !employee.value.code.trim()) {
    return false;
  } else {
    return true;
  }
};

const createMultiple = async (dropzoneFile) => {
  const formData = new FormData();
  formData.append("file", dropzoneFile);
  const response = await api.post(`${apiEmployee}/post-file`, formData, {...config});
  if (response.data.code === 200) {
    isPopupUpload.value = false;
    isPopupMessage.value = true;
    message.value = response.data.data;
    await getListData();
  }
}
const useEmployee = () => {
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
    createEmployee,
    apiEmployee,
    handleDeleteKeyWord,
    handleFilterByWorkStatus,
    handleFilterByPosition,
    employee,
    isPopupCreate,
    deleteMultiItem,
    isPopupSelected,
    isPopupDeleteItems,
    createMultiple,
    isPopupUpload,
    isPopupMessage,
    message,
    onRequest,
  };
};

export { useEmployee };
