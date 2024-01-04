import { defineStore } from "pinia";
import { ref } from "vue";

export const useHrmStore = defineStore("hrm", () => {
  const employees = ref([
    {
      id: "1",
      anh: "../src/assets/avatar2.png",
      name: "NS-025",
      fullname: "Đàm Vĩnh Hưng",
      datebirth: "2023-07-15",
      gender: "Nam",
      position: "P.Trưởng khoa",
      title: "BS chuyên khoa",
      phonenumber: "0912345678",
      address: "Số 1, Đường Láng, Láng Thượng, Đống Đa, Hà Nội",
      manage: "Lê Tiến Tâm",
      classify: "Biên Chế",
      trangThai: "Đang làm việc",
      act: "",
    },
    {
      id: "2",
      anh: "../src/assets/avatar2.png",
      name: "NS-026",
      fullname: "Nguyễn Ngọc Hà",
      datebirth: "2023-08-19",
      gender: "Nữ",
      position: "Y tá trưởng",
      title: "Y tá",
      phonenumber: "0999999999",
      address: "Số 2, Đường Láng, Láng Thượng, Đống Đa, Hà Nội",
      manage: "Mai Ngô Thiên Phú",
      classify: "Hợp đồng",
      trangThai: "Nghỉ thai sản",
      act: "",
    },
  ]);

  return { employees };
});
