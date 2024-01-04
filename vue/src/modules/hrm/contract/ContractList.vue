<template>
  <div class="block-info">
    <div class="q-container">
      <!-- Start: action list -->
      <div class="row q-mt-lg justify-between" style="position: relative">
        <div class="block-title">Danh sách hợp đồng</div>
        <div class="btns-act q-gutter-x-md">
          <q-btn
            @click="handleOpenPopupDeleteItems"
            style="background: #ff0000; color: #ffffff"
            label="Xóa"
            no-caps
          />
          <q-btn
            @click="handleOpenPopupUpload"
            style="background: #236674; color: #ffffff"
            label="Tải lên"
            no-caps
          />
          <q-btn
            @click="handleOpenPopupCreate"
            style="background: #236674; color: #ffffff"
            label="Thêm mới"
            no-caps
          />
        </div>
      </div>
      <div class="row q-mt-lg justify-between wrap">
        <div class="row q-gutter-md">
          <q-select
            style="min-width: 136px"
            standout="bg-teal text-white"
            dense
            v-model="model"
            :options="options"
            label="Chức vụ"
          />
          <q-select
            style="min-width: 118px"
            standout="bg-teal text-white"
            dense
            v-model="model"
            :options="options"
            label="Chức danh"
          />
          <q-select
            style="min-width: 118px"
            standout="bg-teal text-white"
            dense
            v-model="model"
            :options="options"
            label="Phân loại"
          />
        </div>
        <div style="min-width: 320px">
          <q-input v-model="search" dense outlined placeholder="Tìm kiếm">
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </div>
      </div>
      <!-- End: action list -->

      <!-- Start: table -->
      <q-table
        class="q-mt-lg"
        flat
        :rows="rows"
        :columns="columns"
        virtual-scroll
        selection="multiple"
        :separator="'cell'"
        v-model:selected="selected"
      >
        <template v-slot:body="props">
          <q-tr :props="props">
            <q-td>
              <q-checkbox v-model="props.selected" />
            </q-td>

            <template v-for="col in props.cols">
              <template v-if="col.name === 'act'">
                <q-td :key="col.name" :props="props">
                  <ul class="btns-icon">
                    <li @click="editInfo(props.rowIndex)">
                      <q-icon name="fa-solid fa-pen" />
                    </li>
                    <li @click="handleOpenPopupDeleteItem(props.row.name)">
                      <q-icon name="fa-solid fa-trash-can" />
                    </li>
                    <li @click="handleOpenPopupInforItem(props.row.id)">
                      <q-icon name="fa-solid fa-circle-info" />
                    </li>
                  </ul>
                </q-td>
              </template>
              <template v-else>
                <q-td
                  style="vertical-align: middle"
                  :key="col.name"
                  :props="props"
                >
                  {{ col.value }}
                </q-td>
              </template>
            </template>
          </q-tr>
        </template>
      </q-table>
      <!-- End: table -->

      <!-- Start: popup delete 1 item -->
      <q-dialog v-model="isPopupDeleteItem" persistent>
        <q-card style="width: 420px; height: auto; position: relative">
          <q-btn
            style="position: absolute; top: 0; right: 0; z-index: 4"
            icon="close"
            flat
            square
            dense
            v-close-popup
          />
          <q-card-section class="row items-center">
            <q-space />
            <div class="title-del">Xóa nhân sự</div>
            <q-space />
          </q-card-section>

          <q-card-section style="text-align: center"
            >Bạn muốn xóa nhân sự đã chọn ?</q-card-section
          >
          <q-card-actions align="center" style="margin-top: 26px">
            <q-btn
              style="background: #d9d9d9"
              unelevated
              label="Hủy bỏ"
              v-close-popup
            />
            <q-btn
              style="background-color: #2747a0"
              color="text-white"
              unelevated
              label="Đồng ý"
              @click="handleDeleteInfoEmployee"
            />
          </q-card-actions>
        </q-card>
      </q-dialog>
      <!-- End: popup delete 1 item -->

      <!-- Start: popup delete items -->
      <q-dialog v-model="isPopupDeleteItems" persistent>
        <q-card style="width: 420px; height: auto; position: relative">
          <q-btn
            style="position: absolute; top: 0; right: 0; z-index: 4"
            icon="close"
            flat
            square
            dense
            v-close-popup
          />
          <q-card-section class="row items-center">
            <q-space />
            <div class="title-del">Xóa</div>
            <q-space />
          </q-card-section>

          <q-card-section style="text-align: center"
            >Xóa các thông tin đã chọn ?</q-card-section
          >
          <q-card-actions align="center" style="margin-top: 26px">
            <q-btn
              style="background: #d9d9d9"
              unelevated
              label="Hủy bỏ"
              v-close-popup
            />
            <q-btn
              style="background-color: #2747a0"
              color="text-white"
              unelevated
              label="Đồng ý"
            />
          </q-card-actions>
        </q-card>
      </q-dialog>
      <!-- End: popup delete items -->
      
      <!-- Start: popup upload file -->
      <q-dialog v-model="isPopupUpload" persistent>
        <q-card style="width: 450px; height: auto; position: relative">
          <q-btn
            style="position: absolute; top: 0; right: 0; z-index: 4"
            icon="close"
            flat
            square
            dense
            v-close-popup
          />
          <q-card-section class="row items-center" style="display: block">
            <div class="title-upload">Tải lên danh sách</div>
            <div class="file-list q-my-lg">
              <p>File mẫu</p>
              <a href="">{{
                dropzoneFile ? dropzoneFile.name : "danhsachxe.xlsx"
              }}</a>
            </div>
            <drop-zone-upload-info @on-dropzone-file="handleDropZoneFile" />
          </q-card-section>
          <q-card-actions align="center" style="padding: 20px 36px">
            <q-btn
              style="background: #d9d9d9"
              unelevated
              label="Hủy bỏ"
              v-close-popup
            />
            <q-btn
              style="background-color: #2747a0"
              color="text-white"
              unelevated
              label="Lưu lại"
            />
          </q-card-actions>
        </q-card>
      </q-dialog>
      <!-- End: popup upload file -->

      <!-- Start: popup create employee -->
      <q-dialog v-model="isPopupCreate" persistent>
        <q-card style="width: 1110px; max-width: 80vw">
          <q-btn
            style="position: absolute; top: 0; right: 0; z-index: 4"
            icon="close"
            flat
            square
            dense
            v-close-popup
          />
          <q-card-section class="q-pb-none">
            <div class="q-container">
              <div class="form-group mb-10 pt-30">
                <h3 class="text-grey-10 add__title">Thêm mới hợp đồng</h3>
              </div>
              <q-tabs
              
                dense
                class="info-tabs text-grey-10 rounded-borders"
                active-color="cyan-9"
                indicator-color="cyan-9"
                align="justify"
              >
                <q-tab-panel name="create">
                  <div class="row">
                    <div class="form_box mb-10 col-12">
                      <h3>Thông nhân sự</h3>
                      <div class="row">
                        <div class="col-6">
                          <div class=" mr-10">
                            <div class="form-center">
                              <div class="form_group d-flex mb-10">
                                <label class="label-field" for="">Hình thức nghỉ</label>
                                <q-select  outlined v-model="typesBreak" 
                                :options="styleBreak" 
                                :dense="true" 
                                class="input-field_info select-box" 
                                emit-value
                                map-options
                                />
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="ml-10">
                            <div class="form-center">
                              <div class="form_group d-flex mb-10 ">
                                <label >Mã nhân viên:</label>
                                <q-input class="input-box"  outlined v-model="text" :dense="true" />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form_box mb-10 col-12">
                      <h3>Thông hợp đồng</h3>
                      <div class="row">
                        <div class="col-6">
                          <div class=" mr-10">
                            <div class="form-center">
                              <div class="form_group d-flex mb-10">
                                <label for=""
                                  >Mã hợp đồng :
                                </label>
                                <q-input class="input-box" outlined v-model="text" :dense="true" />
                              </div>
                              <div class="form_group d-flex mb-10">
                                <label for=""
                                  >Ngày ký:
                                </label>
                                <q-input class="input-box" type="date" outlined v-model="text" :dense="true" />
                              </div>
                              <div class="form_group d-flex mb-10">
                                <label for=""
                                  >Ngày bắt đầu:
                                </label>
                                <q-input class="input-box" type="date" outlined v-model="text" :dense="true" />
                              </div>
                              <div class="form_group d-flex mb-10">
                                <label for=""
                                  >Chức vụ:
                                </label>
                                <q-input class="input-box" outlined v-model="text" :dense="true" />
                              </div>
                              <div class="form_group d-flex mb-10">
                                <label for=""
                                  >Lương cơ bản :
                                </label>
                                <q-input class="input-box" outlined v-model="text" :dense="true" />
                              </div>
                              <div class="form_group d-flex mb-10">
                                <label for=""
                                  >Mức lương đóng BHXH:
                                </label>
                                <q-input class="input-box" outlined v-model="text" :dense="true" />
                              </div>
                              <div class="form_group d-flex mb-10">
                                <label for=""
                                  >Phụ cấp ăn ca :
                                </label>
                                <q-input class="input-box" outlined v-model="text" :dense="true" />
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="ml-10">
                            <div class="form-center">
                              <div class="form_group d-flex mb-10 ">
                                <label >Loại hơp đồng:</label>
                                <q-select  outlined v-model="typesBreak" 
                                :options="styleBreak" 
                                :dense="true" 
                                class="input-field_info select-box" 
                                emit-value
                                map-options
                                />
                              </div>
                              <div class="form_group d-flex mb-10 ">
                                <label >Thời hạn hơp đồng:</label>
                                <div class="row" style="width:65%;">
                                  <div class="col-6">
                                    <q-select  outlined v-model="typesBreak" 
                                    :options="styleBreak" 
                                    :dense="true" 
                                    class="input-field_info select-box" 
                                    emit-value
                                    map-options
                                    />
                                  </div>
                                  <div class="col-6">
                                    <q-select  outlined v-model="typesBreak" 
                                    :options="styleBreak" 
                                    :dense="true" 
                                    class="input-field_info select-box" 
                                    emit-value
                                    map-options
                                    />
                                  </div>
                                </div>
                              </div>
                              <div class="form_group d-flex mb-10">
                                <label for=""
                                  >Ngày kết thúc :
                                </label>
                                <q-input class="input-box" type="date" outlined v-model="text" :dense="true" />
                              </div>
                              <div class="form_group d-flex mb-10">
                                <label for=""
                                  >Phòng ban :
                                </label>
                                <q-input class="input-box" outlined v-model="text" :dense="true" />
                              </div>
                              <div class="form_group d-flex mb-10">
                                <label for=""
                                  >Lương trách nhiệm :
                                </label>
                                <q-input class="input-box" outlined v-model="text" :dense="true" />
                              </div>
                              <div class="form_group d-flex mb-10">
                                <label for=""
                                  >Mức đóng BHXH :
                                </label>
                                <q-input class="input-box" outlined v-model="text" :dense="true" />
                              </div>
                              <div class="form_group d-flex mb-10">
                                <label for=""
                                  >Phụ cấp xăng xe :
                                </label>
                                <q-input class="input-box" outlined v-model="text" :dense="true" />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form_group d-flex mb-10">
                        <label for=""
                          >Ghi chú :
                        </label>
                        <q-input class="input-box" style="width: 83%;" outlined v-model="text" :dense="true" />
                      </div>
                    </div>
                    <div class="form_box mb-10 col-12">
                      <h3>Thông tin người ký</h3>
                      <div class="row">
                        <div class="col-6">
                          <div class=" mr-10">
                            <div class="form-center">
                              <div class="form_group d-flex mb-10 ">
                                <label >Người đại diện</label>
                                <q-input class="input-box"  outlined v-model="text" :dense="true" />
                              </div>
                              <div class="form_group d-flex mb-10 ">
                                <label >Ngày ký:</label>
                                <q-input class="input-box" type="date"  outlined v-model="text" :dense="true" />
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="ml-10">
                            <div class="form-center">
                              <div class="form_group d-flex mb-10 ">
                                <label >Chức vụ:</label>
                                <q-input class="input-box"  outlined v-model="text" :dense="true" />
                              </div>
                              <div class="form_group d-flex mb-10 ">
                                <label >Mô tả:</label>
                                <q-input class="input-box"  outlined v-model="text" :dense="true" />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </q-tab-panel>
              </q-tabs>
              <q-card-actions align="right" style="padding: 20px 0px">
                <q-btn
                  style="background: #d9d9d9"
                  unelevated
                  label="Hủy bỏ"
                  v-close-popup
                />
                <q-btn
                  style="background-color: #236674"
                  color="text-white"
                  unelevated
                  label="Lưu lại"
                />
              </q-card-actions>
            </div>
          </q-card-section>
        </q-card>
      </q-dialog>
      <!-- End: popup create employee -->

      <!-- Start: popup update employee -->
      <q-dialog v-model="isPopupupdate" persistent>
        <q-card style="width: 1110px; max-width: 80vw">
          <q-btn
            style="position: absolute; top: 0; right: 0; z-index: 4"
            icon="close"
            flat
            square
            dense
            v-close-popup
          />
          <q-card-section class="q-pb-none">
            <div class="q-container">
              <div class="form-group mb-10 pt-30">
                <h3 class="text-grey-10 add__title">Chỉnh sửa hợp đồng</h3>
              </div>
              <q-tabs
              
                dense
                class="info-tabs text-grey-10 rounded-borders"
                active-color="cyan-9"
                indicator-color="cyan-9"
                align="justify"
              >
                <q-tab-panel name="update">
                  <div class="row">
                    <div class="form_box mb-10 col-12">
                      <h3>Thông nhân sự</h3>
                      <div class="row">
                        <div class="col-6">
                          <div class=" mr-10">
                            <div class="form-center">
                              <div class="form_group d-flex mb-10">
                                <label class="label-field" for="">Hình thức nghỉ</label>
                                <q-select  outlined v-model="typesBreak" 
                                :options="styleBreak" 
                                :dense="true" 
                                class="input-field_info select-box" 
                                emit-value
                                map-options
                                />
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="ml-10">
                            <div class="form-center">
                              <div class="form_group d-flex mb-10 ">
                                <label >Mã nhân viên:</label>
                                <q-input class="input-box"  outlined v-model="text" :dense="true" />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form_box mb-10 col-12">
                      <h3>Thông hợp đồng</h3>
                      <div class="row">
                        <div class="col-6">
                          <div class=" mr-10">
                            <div class="form-center">
                              <div class="form_group d-flex mb-10">
                                <label for=""
                                  >Mã hợp đồng :
                                </label>
                                <q-input class="input-box" outlined v-model="text" :dense="true" />
                              </div>
                              <div class="form_group d-flex mb-10">
                                <label for=""
                                  >Ngày ký:
                                </label>
                                <q-input class="input-box" type="date" outlined v-model="text" :dense="true" />
                              </div>
                              <div class="form_group d-flex mb-10">
                                <label for=""
                                  >Ngày bắt đầu:
                                </label>
                                <q-input class="input-box" type="date" outlined v-model="text" :dense="true" />
                              </div>
                              <div class="form_group d-flex mb-10">
                                <label for=""
                                  >Chức vụ:
                                </label>
                                <q-input class="input-box" outlined v-model="text" :dense="true" />
                              </div>
                              <div class="form_group d-flex mb-10">
                                <label for=""
                                  >Lương cơ bản :
                                </label>
                                <q-input class="input-box" outlined v-model="text" :dense="true" />
                              </div>
                              <div class="form_group d-flex mb-10">
                                <label for=""
                                  >Mức lương đóng BHXH:
                                </label>
                                <q-input class="input-box" outlined v-model="text" :dense="true" />
                              </div>
                              <div class="form_group d-flex mb-10">
                                <label for=""
                                  >Phụ cấp ăn ca :
                                </label>
                                <q-input class="input-box" outlined v-model="text" :dense="true" />
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="ml-10">
                            <div class="form-center">
                              <div class="form_group d-flex mb-10 ">
                                <label >Loại hơp đồng:</label>
                                <q-select  outlined v-model="typesBreak" 
                                :options="styleBreak" 
                                :dense="true" 
                                class="input-field_info select-box" 
                                emit-value
                                map-options
                                />
                              </div>
                              <div class="form_group d-flex mb-10 ">
                                <label >Thời hạn hơp đồng:</label>
                                <div class="row" style="width:65%;">
                                  <div class="col-6">
                                    <q-select  outlined v-model="typesBreak" 
                                    :options="styleBreak" 
                                    :dense="true" 
                                    class="input-field_info select-box" 
                                    emit-value
                                    map-options
                                    />
                                  </div>
                                  <div class="col-6">
                                    <q-select  outlined v-model="typesBreak" 
                                    :options="styleBreak" 
                                    :dense="true" 
                                    class="input-field_info select-box" 
                                    emit-value
                                    map-options
                                    />
                                  </div>
                                </div>
                              </div>
                              <div class="form_group d-flex mb-10">
                                <label for=""
                                  >Ngày kết thúc :
                                </label>
                                <q-input class="input-box" type="date" outlined v-model="text" :dense="true" />
                              </div>
                              <div class="form_group d-flex mb-10">
                                <label for=""
                                  >Phòng ban :
                                </label>
                                <q-input class="input-box" outlined v-model="text" :dense="true" />
                              </div>
                              <div class="form_group d-flex mb-10">
                                <label for=""
                                  >Lương trách nhiệm :
                                </label>
                                <q-input class="input-box" outlined v-model="text" :dense="true" />
                              </div>
                              <div class="form_group d-flex mb-10">
                                <label for=""
                                  >Mức đóng BHXH :
                                </label>
                                <q-input class="input-box" outlined v-model="text" :dense="true" />
                              </div>
                              <div class="form_group d-flex mb-10">
                                <label for=""
                                  >Phụ cấp xăng xe :
                                </label>
                                <q-input class="input-box" outlined v-model="text" :dense="true" />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form_group d-flex mb-10">
                        <label for=""
                          >Ghi chú :
                        </label>
                        <q-input class="input-box" style="width: 83%;" outlined v-model="text" :dense="true" />
                      </div>
                    </div>
                    <div class="form_box mb-10 col-12">
                      <h3>Thông tin người ký</h3>
                      <div class="row">
                        <div class="col-6">
                          <div class=" mr-10">
                            <div class="form-center">
                              <div class="form_group d-flex mb-10 ">
                                <label >Người đại diện</label>
                                <q-input class="input-box"  outlined v-model="text" :dense="true" />
                              </div>
                              <div class="form_group d-flex mb-10 ">
                                <label >Ngày ký:</label>
                                <q-input class="input-box" type="date"  outlined v-model="text" :dense="true" />
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="ml-10">
                            <div class="form-center">
                              <div class="form_group d-flex mb-10 ">
                                <label >Chức vụ:</label>
                                <q-input class="input-box"  outlined v-model="text" :dense="true" />
                              </div>
                              <div class="form_group d-flex mb-10 ">
                                <label >Mô tả:</label>
                                <q-input class="input-box"  outlined v-model="text" :dense="true" />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </q-tab-panel>
              </q-tabs>
              <q-card-actions align="right" style="padding: 20px 0px">
                <q-btn
                  @click="handleOpenPopupDeleteItem(id)"
                  style="background: #ff0000; color: #ffffff"
                  label="Xóa"
                  no-caps
                />
                <q-btn
                  style="background: #d9d9d9"
                  unelevated
                  label="Hủy bỏ"
                  v-close-popup
                />
                <q-btn
                  style="background-color: #236674"
                  color="text-white"
                  unelevated
                  label="Lưu lại"
                />
              </q-card-actions>
            </div>
          </q-card-section>
        </q-card>
      </q-dialog>
      <!-- End: popup update employee -->

      <!-- Start: popup details employee -->
      <q-dialog v-model="isPopupDetails" persistent>
        <q-card style="width: 1110px; height: 1160px; max-width: 80vw">
          <q-btn
            style="position: absolute; top: 0; right: 0; z-index: 4"
            icon="close"
            flat
            square
            dense
            v-close-popup
          />
          <q-card-section class="q-pb-none" style="padding: 0">
            <div class="employee-profile__container q-container">
              <q-tab-panels
                v-model="tabsDetails"
                animated
                class="rounded-borders"
              >
               VIẾT TẠI ĐÂY
              </q-tab-panels>
              <q-card-actions align="right" style="padding: 20px 0px">
                <q-btn
                  style="background: #d9d9d9"
                  unelevated
                  label="Hủy bỏ"
                  v-close-popup
                />
                <q-btn
                  style="background-color: #236674"
                  color="text-white"
                  unelevated
                  label="Lưu lại"
                />
              </q-card-actions>
            </div>
          </q-card-section>
        </q-card>
      </q-dialog>
      <!-- End: popup details employee -->
    </div>
  </div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
  name: "EmployeeList",
});
</script>

<script setup>
import { ref } from "vue";
import { useRoute } from "vue-router";
import DropZoneUploadInfo from "../components/dropzone/DropZoneUploadInfo.vue";
const selected = ref([]);
const columns = [
  {
    name: "signDay",
    label: "Ngày Ký",
    align: "left",
    field: "signDay",
    sortable: true,
  },
  {
    name: "contractCode",
    required: true,
    label: "Số HĐ",
    align: "left",
    field: (row) => row.name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "fullname",
    align: "left ",
    label: "Họ Tên NLĐ ",
    field: "fullname",
    sortable: true,
  },
  {
    name: "position",
    align: "left",
    label: "Chức Vụ",
    field: "position",
    sortable: true,
  },  
  {
    name: "Department",
    align: "left",
    label: "Phòng Ban",
    field: "Department",
    sortable: true,
  },
  {
    name: "type",
    label: "Loại Hợp Đồng",
    align: "left",
    field: "type",
    sortable: true,
  },
  {
    name: "duration",
    label: "Thời Hạn",
    align: "left",
    field: "duration",
    sortable: true,
  },
  {
    name: "status",
    label: "Trạng thái",
    align: "left",
    field: "status",
    sortable: true,
  },
  {
    name: "act",
    label: "Hành động",
    align: "center",
    field: "act",
  },
];

const rows = ref([
  {
    id: "1",
    anh: "../src/assets/avatar2.png",
    name: "NS-025",
    fullname: "Đàm Vĩnh Hưng",
    Department: "Kế toán",
    type: "Hợp Đồng Lao Động",
    position: "P.Trưởng khoa",
    title: "BS chuyên khoa",
    duration: "1",
    address: "Số 1, Đường Láng, Láng Thượng, Đống Đa, Hà Nội",
    manage: "Lê Tiến Tâm",
    signDay: "20-11-2023",
    status: "Đang làm việc",
    act: "",
  },
  {
    id: "2",
    anh: "../src/assets/avatar2.png",
    name: "NS-026",
    fullname: "Nguyễn Ngọc Hà",
    Department: "Kế toán",
    type: "Hợp Đồng Lao Động",
    position: "Y tá trưởng",
    title: "Y tá",
    duration: "3",
    address: "Số 2, Đường Láng, Láng Thượng, Đống Đa, Hà Nội",
    manage: "Mai Ngô Thiên Phú",
    signDay: "20-11-2023",
    status: "Nghỉ thai sản",
    act: "",
  },
]);


const dropzoneFile = ref("");
const isPopupDeleteItem = ref(false);
const isPopupDeleteItems = ref(false);
const isPopupUpload = ref(false);
const isPopupCreate = ref(false);
const isPopupDetails = ref(false);
const isPopupupdate = ref(false);
const route = useRoute();
const tab = ref("create");
const tabsDetails = ref("infoPerson");
const contractCode = ref("");

const employeeInfo = ref({
  id: "",
  name: "",
  fullname: "",
  datebirth: "",
  gender: "",
  mobilenumber: "",
  homenumber: "",
  address: "",
  noiSinh: "",
  email: "",
  quocTich: "",
  danToc: "",
  tonGiao: "",
  status: "",
  anh: "",
});

const handleOpenPopupDeleteItem = (e) => {
  console.log(e);
  isPopupDeleteItem.value = true;
  contractCode.value = e;
};

const handleDeleteInfoEmployee = () => {
      rows.value = rows.value.filter(
        (item) => item.name !== contractCode.value
      );
      isPopupDeleteItem.value = false;
    };

const handleOpenPopupDeleteItems = () => {
  isPopupDeleteItems.value = true;
};

const handleOpenPopupUpload = () => {
  isPopupUpload.value = true;
};

const handleOpenPopupCreate = () => {
  isPopupCreate.value = true;
};

const handleDropZoneFile = (e) => {
  dropzoneFile.value = e;
};

const handleOpenPopupInforItem = (id) => {
  isPopupDetails.value = true;
  employeeInfo.value = rows.value.find((it) => it.id === id);
};

const editInfo = (id) => {
  isPopupupdate.value = true;
  employeeInfo.value = rows.value.find((it) => it.id === id);
};
</script>

<style lang="scss" scoped>
@import "assets/scss/common.scss";
@import "./assest/ContractList.scss";
@import "./assest/popup/ContractCreate.scss";
@import "./assest/popup/ContractDetails.scss";
</style>
<style scoped>
:deep(.input-box .q-field__control),
:deep(.input-box .q-field__marginal) {
  height: 32px;
}
:deep(.select-box .q-field__control),
:deep(.select-box .q-field__marginal),
:deep(.select-box .q-field__native) {
  height: 32px;
  min-height: 0;
}
</style>