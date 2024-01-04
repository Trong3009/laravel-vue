<template>
  <div class="block-info">
    <div class="q-container">
      <!-- Start: action list -->
      <div class="row q-mt-lg justify-between" style="position: relative">
        <div class="block-title">Danh sách nhân sự</div>
        <div class="btns-act q-gutter-x-md">
          <q-btn
            @click="handleOpenPopupDeleteItems"
            style="background: #ff0000; color: #ffffff"
            label="Xóa"
            no-caps
          />
<!--          <q-btn-->
<!--            @click="handleOpenPopupUpload"-->
<!--            style="background: #236674; color: #ffffff"-->
<!--            label="Tải lên"-->
<!--            no-caps-->
<!--          />-->
          <q-btn
            @click="handleOpenPopupCreate"
            style="background: #236674; color: #ffffff"
            label="Thêm mới"
            no-caps
          />
          <q-icon
            class="list-vertical"
            name="fa-solid fa-ellipsis-vertical"
            @click="isDropdownVisible = !isDropdownVisible"
          />
          <div
            v-if="isDropdownVisible"
            class="dropdown-menu q-mt-md"
            style="background-color: #ffffff; border-radius: 10px"
          >
            <q-item>
              <q-item-section class="dropdown-action" @click="handleOpenPopupUpload">Tải lên</q-item-section>
            </q-item>
            <q-item>
              <q-item-section class="dropdown-action" @click="handleDownloadExcel">Tải xuống</q-item-section>
            </q-item>
          </div>
        </div>
      </div>
      <div class="row q-mt-lg justify-between wrap">
        <div class="row q-gutter-md">
          <q-select
            style="min-width: 136px"
            standout="bg-teal text-white"
            dense
            label="Chức vụ"
            emit-value
            map-options
            v-model="filter.position"
            :options="positionOptions"
            @update:model-value="(val) => handleFilterByPosition(val)"
          />
          <q-select
            style="min-width: 118px"
            standout="bg-teal text-white"
            dense
            label="Trạng thái"
            v-model="filter.work_status"
            emit-value
            map-options
            :options="workStatusOptions"
            @update:model-value="(val) => handleFilterByWorkStatus(val)"
          />
        </div>
        <div style="min-width: 320px">
          <q-input v-model="filter.keyword" dense outlined placeholder="Tìm kiếm" @keyup.enter="getListData">
            <template v-slot:append>
              <q-icon
                v-if="filter.keyword !== ''"
                name="close"
                @click="handleDeleteKeyWord"
                class="cursor-pointer"
              />
              <q-icon
                name="search"
                @click="getListData"
              />
            </template>
          </q-input>
        </div>
      </div>
      <!-- End: action list -->

      <!-- Start: table danh sach nv-->
      <q-table
        class="q-mt-lg"
        flat
        :rows="rows"
        :columns="columns"
        :loading="isLoading"
        selection="multiple"
        :separator="'cell'"
        v-model:selected="selected"
        v-model:pagination="pagination"
        :rows-per-page-options="rowsPerPageOptions"
        rows-per-page-label="Hiển thị"
        :binary-state-sort="true"
        @request="onRequest"
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
                    <li @click="handleOpenPopupEditItem(props.row.id)">
                      <q-icon name="fa-solid fa-pen" />
                    </li>
                    <li @click="handleOpenPopupDeleteItem(props.row.id)">
                      <q-icon name="fa-solid fa-trash-can" />
                    </li>
                    <li @click="handleOpenPopupInfoItem(props.row.id)">
                      <q-icon name="fa-solid fa-circle-info" />
                    </li>
                  </ul>
                </q-td>
              </template>
              <template v-else-if="col.name === 'avatar'">
                <q-td
                  style="vertical-align: middle"
                  :key="col.name"
                  :props="props"
                >
                  <img
                    v-if="props.row.avatar"
                    style="border-radius: 50%"
                    width="40"
                    height="40"
                    :src="props.row.avatar"
                    alt=""
                  />
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

      <!-- show popup khi ko click chon selected -->
      <q-dialog v-model="isPopupSelected" persistent>
        <q-card style="width: 420px; height: auto; position: relative">
          <q-btn
            style="position: absolute; top: 0; right: 0; z-index: 4"
            icon="close"
            flat
            square
            dense
            v-close-popup
          />
          <q-card-section style="text-align: center">Vui lòng chọn thông tin ?</q-card-section>
          <q-card-actions align="center" style="margin-top: 26px">
            <q-btn
              style="background-color: #2747a0"
              color="text-white"
              unelevated
              label="Đồng ý"
              v-close-popup
            />
          </q-card-actions>
        </q-card>
      </q-dialog>
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

          <q-card-section style="text-align: center">Xóa các thông tin đã chọn ?</q-card-section>
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
              @click="handleDeleteInfoEmployees"
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
              <p>File upload:</p>
              <a href="">{{
                dropzoneFile ? dropzoneFile.name : "danh-sach-NV.xlsx"
              }}</a>
            </div>
            <drop-zone-upload-info @dropzone-file="handleDropZoneFile" />
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
              @click="submitCreateMultiple"
            />
          </q-card-actions>
        </q-card>
      </q-dialog>
      <!-- End: popup upload file -->

      <create-employee
        v-model="isPopupCreate"
        :prop="isEdit"
        @create-employee="createData($event)"
        @update-employee="updateData($event)"
      />
      <popup-message
        v-model="isPopupMessage"
        :message="message"
      />

      <!-- Start: popup details employee -->
      <q-dialog v-model="isPopupDetails" persistent>
        <q-card style="width: 1110px; height: 1160px; max-width: 80vw">
          <div class="info-person__top">
            <div class="container">
              <div class="arrow-back">
                <q-icon name="fa-solid fa-arrow-left" v-close-popup />
              </div>
              <div class="left">
                <img :src="employee.avatar" />
              </div>
              <div class="right ml-10">
                <h3>
                  {{ employee.name }}
                </h3>
                <p>Trưởng khoa - Khoa Khúc xạ</p>
              </div>
            </div>
            <div class="button-fix">
              <q-btn
                style="background: #236674"
                unelevated
                icon="fa-solid fa-file-pen"
                label="Sửa"
                v-close-popup
              />
            </div>
          </div>
          <q-tabs
            v-model="tabsDetails"
            dense
            class="info-tabs text-grey-10"
            active-color="cyan-9"
            indicator-color="cyan-9"
            align="justify"
          >
            <q-tab name="infoPerson" label="Thông tin cá nhân"></q-tab>
            <q-tab name="infoWork" label="Thông tin làm việc"></q-tab>
            <q-tab name="infoProgess" label="Quá trình công tác"></q-tab>
            <q-tab name="infoSalary" label="Lịch sử lương"></q-tab>
            <q-tab name="historySalary" label="Lịch sử chi trả lương"></q-tab>
            <q-tab name="infoExtra" label="Thưởng, phạt"></q-tab>
          </q-tabs>
          <q-card-section class="q-pb-none" style="padding: 0">
            <div class="employee-profile__container q-container">
              <q-tab-panels
                v-model="tabsDetails"
                animated
                class="rounded-borders"
              >
                <q-tab-panel name="infoPerson">
                  <div class="row">
                    <div class="form-left__container col-7">
                      <div class="form_box mb-10">
                        <div class="form_top">
                          <h3>Thông tin chung</h3>
                          <div class="image-upload">
                            <img class="preview-image" :src="employee.avatar" alt="" style="height: 100% !important;">
                          </div>
                          <div class="form_group d-flex mb-10">
                            <label for="employeecode"
                            >Mã nhân viên <span>*</span>:
                            </label>
                            <input type="text" id="employeecode"
                                   v-model="employee.code" disabled
                            />
                          </div>

                          <div class="form_group d-flex mb-10">
                            <label>Họ và tên <span>*</span>:</label>
                            <input type="text"
                                   v-model="employee.name" disabled
                            />
                          </div>

                          <div class="form_group d-flex mb-10">
                            <label>Ngày sinh <span>*</span>:</label>
                            <input type="date"
                                   v-model="employee.birthday" disabled
                            />
                          </div>

                          <div class="form_group d-flex mb-10">
                            <label>Nơi sinh:</label>
                            <input v-model="employee.place_of_birth" type="text" disabled />
                          </div>

                          <div class="form_group d-flex mb-10">
                            <label>Giới tính:</label>
                            <div class="input-radio">
                              <div class="input-options">
                                <input
                                  type="radio"
                                  name="gender"
                                  value="0"
                                  v-model="employee.gender" disabled
                                />
                                <label for="boy">Nam</label>
                              </div>
                              <div class="input-options">
                                <input
                                  type="radio"
                                  name="gender"
                                  value="1"
                                  v-model="employee.gender" disabled
                                />
                                <label for="girl">Nữ</label>
                              </div>
                              <div class="input-options">
                                <input
                                  type="radio"
                                  name="gender"
                                  value="2"
                                  v-model="employee.gender" disabled
                                />
                                <label for="other">Khác</label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-center">
                          <div class="form_group d-flex mb-10">
                            <label>Địa chỉ thường trú:</label>
                            <input type="text"
                                   v-model="employee.permanent_address" disabled
                            />
                          </div>

                          <div class="form_group d-flex mb-10">
                            <label>Địa chỉ tạm quán:</label>
                            <input type="text"
                                   v-model="employee.residence_address" disabled
                            />
                          </div>

                          <div class="form_group d-flex mb-10">
                            <label>Nguyên quán:</label>
                            <input type="text"
                                   v-model="employee.domicile" disabled
                            />
                          </div>

                          <div class="form_group d-flex mb-10">
                            <label>Số điện thoại <span>*</span>:</label>
                            <input id="phonenumber" type="text"
                                   v-model="employee.phone" disabled
                            />
                          </div>

                          <div class="form_group d-flex mb-10">
                            <label for="email">Email <span>*</span>:</label>
                            <input id="email" type="text"
                                   v-model="employee.email" disabled
                            />
                          </div>
                        </div>
                        <div class="form-bottom">
                          <div class="form_group d-flex mb-10">
                            <label>Quốc tịch:</label>
                            <input id="email" type="text"
                                   class="form-control"
                                   v-model="employee.nationality" disabled
                            />
                          </div>
                          <div class="form_group d-flex mb-10">
                            <label>Dân tộc:</label>
                            <input id="email" type="text"
                                   class="form-control"
                                   v-model="employee.nation" disabled
                            />
                          </div>
                          <div class="form_group d-flex mb-10">
                            <label>Tôn giáo:</label>
                            <input id="email" type="text"
                                   class="form-control"
                                   v-model="employee.religion" disabled
                            />
                          </div>
                          <div class="form_group d-flex mb-10">
                            <label for="namedad">Họ tên cha:</label>
                            <div class="select-options">
                              <input id="name_father" type="text"
                                     v-model="employee.name_father" disabled
                              />
                              <div class="year-birthday">
                                <label for="yearbirthday">Năm sinh:</label>
                                <input id="yearbirthday" type="text"
                                       v-model="employee.birthday_father" disabled
                                />
                              </div>
                            </div>
                          </div>
                          <div class="form_group d-flex mb-10">
                            <label for="namemom">Họ tên mẹ:</label>
                            <div class="select-options">
                              <input id="namemom" type="text"
                                     v-model="employee.name_mother" disabled
                              />
                              <div class="year-birthday">
                                <label for="yearbirthdaymom">Năm sinh:</label>
                                <input id="yearbirthdaymom" type="text"
                                       v-model="employee.birthday_mother" disabled
                                />
                              </div>
                            </div>
                          </div>
                          <div class="form_group d-flex mb-10">
                            <label>Tình trạng hôn nhân:</label>
                            <div class="select-options">
                              <select name="lang" id="lang-select" v-model="employee.marital_status" disabled>
                                <option value="0" selected>Độc thân</option>
                                <option value="1" selected>Đã kết hôn</option>
                              </select>
                            </div>
                          </div>
                          <div class="form_group d-flex mb-10">
                            <label for="namecouple">Họ tên vợ (chồng):</label>
                            <div class="select-options">
                              <input id="namecouple" type="text"
                                     v-model="employee.name_wife_husband" disabled
                              />
                              <div class="year-birthday">
                                <label for="yearbirthdaycouple"
                                >Năm sinh:</label
                                >
                                <input id="yearbirthdaycouple" type="text"
                                       v-model="employee.birthday_wife_husband" disabled
                                />
                              </div>
                            </div>
                          </div>
                          <div class="form-center">
                            <div class="form_group d-flex mb-10">
                              <label for="note">Ghi chú:</label>
                              <input id="note" type="text" v-model="employee.note_user" disabled/>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="form_box mb-10">
                        <h3>Thông tin liên lạc</h3>
                        <div class="form-center">
                          <div class="form_group d-flex mb-10">
                            <label for="person_contact">Người liên hệ:</label>
                            <input id="person_contact" type="text"
                                   v-model="employee.person_contact" disabled
                            />
                          </div>
                          <div class="form_group d-flex mb-10">
                            <label for="person_address">Địa chỉ:</label>
                            <input type="text"
                                   v-model="employee.person_address" disabled
                            />
                          </div>
                          <div class="form_group d-flex mb-10">
                            <label for="person_phone">Số điện thoại:</label>
                            <input id="person_phone" type="text"
                                   v-model="employee.person_phone" disabled
                            />
                          </div>
                          <div class="form_group d-flex mb-10">
                            <label for="person_email">Email:</label>
                            <input id="person_email" type="text"
                                   v-model="employee.person_email" disabled
                            />
                          </div>
                        </div>
                      </div>

                      <div class="form_box">
                        <h3>Phương tiện di chuyển</h3>
                        <div class="form-center">
                          <div class="form_group d-flex mb-10">
                            <label>Loại phương tiện</label>
                            <input class="form-control" type="text"
                                   v-model="employee.vehicle_type" disabled
                            />
                          </div>
                          <div class="form_group d-flex mb-10">
                            <label for="person_address">Tên phương tiện:</label>
                            <input class="form-control" type="text"
                                   v-model="employee.vehicle_name" disabled
                            />
                          </div>
                          <div class="form_group d-flex mb-10">
                            <label for="person_phone">Biển kiểm soát:</label>
                            <input class="form-control" type="text"
                                   v-model="employee.vehicle_number" disabled
                            />
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-right__container col-5 pl-30">
                      <div class="form_box mb-10">
                        <h3>Căn cước công dân/Hộ chiếu:</h3>
                        <div class="id-card">
                          <div class="form_group d-flex mb-10">
                            <label for="type_of_documents">Loại giấy tờ:</label>
                            <select name="type_of_documents" id="type_of_documents"
                                    class="form-control"
                                    v-model="employee.type_of_documents" disabled
                            >
                              <option value="0" selected>CCCD</option>
                              <option value="1" selected>Hộ chiếu</option>
                            </select>
                          </div>

                          <div class="form_group d-flex mb-10">
                            <label for="identification">Số CCCD/HC:</label>
                            <input
                              class="form-control"
                              id="identification"
                              type="text"
                              v-model="employee.identity_card" disabled
                            />
                          </div>

                          <div class="form_group d-flex mb-10">
                            <label>Ngày cấp:</label>
                            <input class="form-control" type="date"
                                   v-model="employee.date_identity_card" disabled
                            />
                          </div>

                          <div class="form_group d-flex mb-10">
                            <label for="issuedby">Nơi cấp:</label>
                            <input
                              class="form-control"
                              id="issuedby"
                              type="text"
                              v-model="employee.address_identity_card" disabled
                            />
                          </div>
                        </div>
                      </div>
                      <div class="form_box mb-10">
                        <h3>Thông tin Đảng viên</h3>
                        <div class="id-card">
                          <div class="form_group d-flex mb-10">
                            <label>Là đảng viên:</label>
                            <div style="width: 55%">
                              <input
                                style="height: 20px; width: 20px"
                                type="checkbox" value="1"
                                v-model="employee.is_member_communist" disabled
                              />
                            </div>
                          </div>
                          <div class="form_group d-flex mb-10">
                            <label for="partycard">Số thẻ đảng:</label>
                            <input
                              class="form-control"
                              id="partycard"
                              type="text"
                              v-model="employee.number_member_communist" disabled
                            />
                          </div>

                          <div class="form_group d-flex mb-10">
                            <label>Ngày kết nạp:</label>
                            <input class="form-control" type="date"
                                   v-model="employee.date_communist" disabled
                            />
                          </div>

                          <div class="form_group d-flex mb-10">
                            <label for="admissionplace">Nơi kết nạp:</label>
                            <input
                              class="form-control"
                              id="admissionplace"
                              type="text"
                              v-model="employee.address_communist" disabled
                            />
                          </div>
                        </div>
                      </div>
                      <div class="form_box mb-10">
                        <h3>Tình trạng sức khỏe</h3>
                        <div class="id-card">
                          <div class="form_group d-flex mb-10">
                            <label for="health_condition"
                            >Tình trạng sức khỏe:</label
                            >
                            <input
                              class="form-control"
                              id="health_condition"
                              type="text"
                              v-model="employee.health_condition" disabled
                            />
                          </div>

                          <div class="form_group d-flex mb-10">
                            <label for="height">Chiều cao:</label>
                            <input
                              class="form-control"
                              id="height"
                              type="text"
                              v-model="employee.height" disabled
                            />
                          </div>

                          <div class="form_group d-flex mb-10">
                            <label for="weight">Cân nặng:</label>
                            <input
                              class="form-control"
                              id="weight"
                              type="text"
                              v-model="employee.weight" disabled
                            />
                          </div>
                          <div class="form_group d-flex mb-10">
                            <label for="note_health_condition">Ghi chú:</label>
                            <input
                              class="form-control"
                              id="note_health_condition"
                              type="text"
                              v-model="employee.note_health_condition" disabled
                            />
                          </div>
                        </div>
                      </div>
                      <div class="form_box mb-10">
                        <h3>Thông tin thanh toán</h3>
                        <div class="id-card">
                          <div class="form_group d-flex mb-10">
                            <label for="bank_number">Số tài khoản:</label>
                            <input
                              class="form-control"
                              id="bank_number"
                              type="text"
                              v-model="employee.bank_number" disabled
                            />
                          </div>

                          <div class="form_group d-flex mb-10">
                            <label for="bank_name">Ngân hàng:</label>
                            <input class="form-control" id="bank_name" type="text"
                                   v-model="employee.bank_name" disabled
                            />
                          </div>

                          <div class="form_group d-flex mb-10">
                            <label for="bank_branch">Chi nhánh:</label>
                            <input
                              class="form-control"
                              id="bank_branch"
                              type="text"
                              v-model="employee.bank_branch" disabled
                            />
                          </div>

                          <div class="form_group d-flex mb-10">
                            <label for="bank_account">Chủ tài khoản:</label>
                            <input
                              class="form-control"
                              id="bank_account"
                              type="text"
                              v-model="employee.bank_account" disabled
                            />
                          </div>
                        </div>
                      </div>
                      <div class="form_box mb-10">
                        <h3>Trình độ chuyên môn</h3>
                        <div class="id-card">
                          <div class="form_group d-flex mb-10">
                            <label for="transfer_level">Học hàm, học vị:</label>
                            <input
                              class="form-control"
                              id="transfer_level"
                              type="text"
                              v-model="employee.transfer_level" disabled
                            />
                          </div>
                          <div class="form_group d-flex mb-10">
                            <label for="training_units">Đơn vị đào tạo:</label>
                            <input
                              class="form-control"
                              id="training_units"
                              type="text"
                              v-model="employee.training_units" disabled
                            />
                          </div>

                          <div class="form_group d-flex mb-10">
                            <label for="specialize">Chuyên ngành:</label>
                            <input
                              class="form-control"
                              id="specialize"
                              type="text"
                              v-model="employee.specialize" disabled
                            />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </q-tab-panel>
                <q-tab-panel name="infoWork">
                  <div class="form_box mb-10">
                    <h3>Thông tin làm việc</h3>
                    <div class="form-info-work row">
                      <div class="col-6">
                        <div class="id-card_left">
                          <div class="form_group d-flex mb-10">
                            <label for="probation_day">Ngày thử việc:</label>
                            <input
                              class="form-control"
                              id="probation_day"
                              type="date"
                              v-model="employee.probation_day" disabled
                            />
                          </div>
                          <div class="form_group d-flex mb-10">
                            <label for="department_id">Phòng ban:</label>
                            <div class="work-info">
                              <select name="department_id" id="department_id" v-model="employee.department_id" disabled>
                                <option value="" selected>Chọn phòng ban</option>
                                <option value="0" selected>Phòng NSHC</option>
                                <option value="1" selected>Sản xuất</option>
                              </select>
                            </div>
                          </div>
                          <div class="form_group d-flex mb-10">
                            <label for="position">Chức vụ:</label>
                            <div class="work-info">
                              <select name="position" id="position" v-model="employee.position" disabled>
                                <option value="" selected>Chọn chức vụ</option>
                                <option value="Giám đốc" selected>Giám đốc</option>
                                <option value="BA" selected>BA</option>
                                <option value="Tester" selected>Tester</option>
                                <option value="Dev" selected>Dev</option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-6 pl-30">
                        <div class="id-card_right">
                          <div class="card-date_job">
                            <div class="form_group d-flex mb-10">
                              <label for="official_day">Ngày chính thức:</label>
                              <input class="form-date" type="date"
                                     v-model="employee.official_day" disabled
                              />
                            </div>
                          </div>

                          <div class="form_group d-flex mb-10">
                            <label>Quản lí trực tiếp:</label>
                            <div class="work-info">
                              <select name="parent_id" v-model="employee.parent_id" disabled>
                                <option value="" selected>Chọn người quản lý</option>
                                <option value="1" selected>Nguyen Van A</option>
                                <option value="2" selected>Nguyen Van B</option>
                              </select>
                            </div>
                          </div>

                          <div class="form_group d-flex mb-10">
                            <label>Chức danh:</label>
                            <input name="job_title" type="text" class="form-control"
                                   v-model="employee.job_title" disabled
                            />
                          </div>
                        </div>
                      </div>
                      <div class="card-note">
                        <div class="form_group d-flex mb-10">
                          <label for="work_note">Ghi chú:</label>
                          <input class="form-note" id="nodework" type="text"
                                 v-model="employee.work_note" disabled
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form_box mb-10">
                    <h3>Trạng thái làm việc</h3>
                    <div class="form-info-work row">
                      <div class="col-6">
                        <div class="id-card_left">
                          <div class="form_group d-flex mb-10">
                            <label>Trạng thái:</label>
                            <div class="work-info">
                              <select name="work_status" v-model="employee.work_status" disabled>
                                <option value="" selected>Chọn trạng thái</option>
                                <option value="1" selected>Đang làm việc</option>
                                <option value="2" selected>Đã nghỉ việc</option>
                                <option value="3" selected>Nghỉ tạm thời</option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-6 pl-30">
                        <div class="card-date_job">
                          <div class="form_group d-flex mb-10">
                            <label>Ngày nghỉ việc:</label>
                            <input class="form-date" type="date"
                                   v-model="employee.quit_date" disabled
                            />
                          </div>
                        </div>
                      </div>
                      <div class="card-note">
                        <div class="form_group d-flex mb-10">
                          <label>Ghi chú:</label>
                          <input
                            class="form-note"
                            type="text"
                            v-model="employee.quit_reason" disabled
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form_box mb-10">
                    <h3>Thông tin lương</h3>
                    <div class="form-info-work row">
                      <div class="col-6">
                        <div class="id-card_left">
                          <div class="form_group d-flex mb-10">
                            <label>Lương cơ bản:</label>
                            <input
                              class="form-control"
                              type="text"
                              v-model="employee.basic_salary" disabled
                            />
                          </div>

                          <div class="form_group d-flex mb-10">
                            <label>Phụ cấp ăn trưa:</label>
                            <input
                              class="form-control"
                              type="text"
                              v-model="employee.meal_allowance" disabled
                            />
                          </div>
                          <div class="form_group d-flex mb-10">
                            <label>Mức lương đóng BHXH:</label>
                            <input
                              class="form-control"
                              type="text"
                              v-model="employee.insurance_salary" disabled
                            />
                          </div>
                        </div>
                      </div>
                      <div class="col-6 pl-30">
                        <div class="id-card_right">
                          <div class="form_group d-flex mb-10">
                            <label>Lương trách nhiệm:</label>
                            <input
                              class="form-control"
                              type="text"
                              v-model="employee.responsibility_salary" disabled
                            />
                          </div>
                          <div class="form_group d-flex mb-10">
                            <label>Phụ cấp xăng xe:</label>
                            <input
                              class="form-control"
                              type="text"
                              v-model="employee.travel_allowance" disabled
                            />
                          </div>
                          <div class="form_group d-flex mb-10">
                            <label>Mức đóng BHXH:</label>
                            <input
                              class="form-control"
                              type="text"
                              v-model="employee.insurance_amount" disabled
                            />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </q-tab-panel>
                <q-tab-panel name="infoProgess">
                  <div class="title-details">
                    <h3>Quá trình công tác</h3>
                    <q-btn
                      class="custom-btn"
                      style="background: #ffffff"
                      unelevated
                      icon="fa-solid fa-plus"
                      label="Thêm mới"
                      v-close-popup
                    />
                  </div>
                  <q-table
                    class="q-mt-lg"
                    flat
                    :rows="rowsWorkingProcess"
                    :columns="columnsWorkingProcess"
                    virtual-scroll
                    hide-pagination
                  >
                    <template v-slot:body="props">
                      <q-tr :props="props">
                        <template v-for="col in props.cols">
                          <template v-if="col.name === 'act'">
                            <q-td :key="col.name" :props="props">
                              <ul class="btns-icon">
                                <li @click="editInfo(props.rowIndex)">
                                  <q-icon name="fa-solid fa-pen" />
                                </li>
                                <li @click="handleOpenPopupDeleteItem()">
                                  <q-icon name="fa-solid fa-trash-can" />
                                </li>
                                <li
                                  @click="
                                    handleOpenPopupInfoItem(props.row.id)
                                  "
                                >
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
                </q-tab-panel>
                <q-tab-panel name="infoSalary">
                  <div class="title-details">
                    <h3>Lương và phụ cấp</h3>
                  </div>
                  <div class="info-salary">
                    <div class="title-details">
                      <h3>Lịch sử lương</h3>
                      <q-btn
                        class="custom-btn"
                        style="background: #ffffff"
                        unelevated
                        icon="fa-solid fa-plus"
                        label="Thêm mới"
                        v-close-popup
                      />
                    </div>
                    <q-table
                      class="q-mt-lg"
                      flat
                      :rows="rowSalaryHistory"
                      :columns="columnsSalaryHistory"
                      virtual-scroll
                      hide-pagination
                    >
                      <template v-slot:body="props">
                        <q-tr :props="props">
                          <template v-for="col in props.cols">
                            <template v-if="col.name === 'act'">
                              <q-td :key="col.name" :props="props">
                                <ul class="btns-icon">
                                  <li @click="editInfo(props.rowIndex)">
                                    <q-icon name="fa-solid fa-pen" />
                                  </li>
                                  <li @click="handleOpenPopupDeleteItem()">
                                    <q-icon name="fa-solid fa-trash-can" />
                                  </li>
                                  <li
                                    @click="
                                      handleOpenPopupInfoItem(props.row.id)
                                    "
                                  >
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
                  </div>
                  <div class="info-salary" style="margin-top: 30px">
                    <div class="title-details">
                      <h3>Phụ cấp</h3>
                      <q-btn
                        class="custom-btn"
                        style="background: #ffffff"
                        unelevated
                        icon="fa-solid fa-plus"
                        label="Thêm mới"
                        v-close-popup
                      />
                    </div>
                    <q-table
                      class="q-mt-lg"
                      flat
                      :rows="rowAllowance"
                      :columns="columnAllowance"
                      virtual-scroll
                      hide-pagination
                    >
                      <template v-slot:body="props">
                        <q-tr :props="props">
                          <template v-for="col in props.cols">
                            <template v-if="col.name === 'act'">
                              <q-td :key="col.name" :props="props">
                                <ul class="btns-icon">
                                  <li @click="editInfo(props.rowIndex)">
                                    <q-icon name="fa-solid fa-pen" />
                                  </li>
                                  <li @click="handleOpenPopupDeleteItem()">
                                    <q-icon name="fa-solid fa-trash-can" />
                                  </li>
                                  <li
                                    @click="handleOpenPopupInfoItem(props.row.id)"
                                  >
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
                  </div>
                </q-tab-panel>
                <q-tab-panel name="historySalary">
                  <div class="title-details">
                    <h3>Lịch sử chi trả lương</h3>
                  </div>
                  <q-table
                    class="q-mt-lg"
                    flat
                    :rows="rowsHistorySalary"
                    :columns="columnsHistorySalary"
                    virtual-scroll
                    hide-pagination
                  >
                    <template v-slot:body="props">
                      <q-tr :props="props">
                        <template v-for="col in props.cols">
                          <template v-if="col.name === 'act'">
                            <q-td :key="col.name" :props="props">
                              <ul class="btns-icon">
                                <li @click="editInfo(props.rowIndex)">
                                  <q-icon name="fa-solid fa-pen" />
                                </li>
                                <li @click="handleOpenPopupDeleteItem()">
                                  <q-icon name="fa-solid fa-trash-can" />
                                </li>
                                <li
                                  @click="
                                    handleOpenPopupInfoItem(props.row.id)
                                  "
                                >
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
                </q-tab-panel>
                <q-tab-panel name="infoExtra">
                  <div class="title-details">
                    <h3>Lịch sử thưởng, phạt</h3>
                  </div>
                  <q-table
                    class="q-mt-lg"
                    flat
                    :rows="rowsInfoExtra"
                    :columns="columnsInfoExtra"
                    virtual-scroll
                    hide-pagination
                  >
                    <template v-slot:body="props">
                      <q-tr :props="props">
                        <template v-for="col in props.cols">
                          <template v-if="col.name === 'act'">
                            <q-td :key="col.name" :props="props">
                              <ul class="btns-icon">
                                <li @click="editInfo(props.rowIndex)">
                                  <q-icon name="fa-solid fa-pen" />
                                </li>
                                <li @click="handleOpenPopupDeleteItem()">
                                  <q-icon name="fa-solid fa-trash-can" />
                                </li>
                                <li
                                  @click="
                                    handleOpenPopupInfoItem(props.row.id)
                                  "
                                >
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
                </q-tab-panel>
              </q-tab-panels>
              <q-card-actions align="right" style="padding: 20px 0px">
                <q-btn
                  color="grey"
                  unelevated
                  label="Close"
                  v-close-popup
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
import { defineComponent, isProxy, toRaw } from "vue";

export default defineComponent({
  name: "EmployeeList",
});
</script>

<script setup>
import {onMounted, ref} from "vue";
import {useEmployee} from "src/modules/hrm/composables/useEmployee";
import DropZoneUploadInfo from "../components/dropzone/DropZoneUploadInfo.vue";
import CreateEmployee from "./CreateEmployee.vue";
import {date} from "quasar";
import { appConfig } from '/define.config.js';
import PopupMessage from "src/modules/hrm/components/PopupMessage.vue";
import {api} from "boot/axios";
import {config} from "src/modules/hrm/constants";

const workStatusOptions = appConfig.workStatus;
const positionOptions = appConfig.positionOptions;

const rowsPerPageOptions = ref([10, 20, 50, 100]);

const columns = [
  {
    name: "avatar",
    label: "Ảnh",
    align: "center",
    field: "avatar",
    sortable: false,
  },
  {
    name: "code",
    label: "Mã NV",
    align: "left",
    field: "code",
    sortable: true, sort: (a, b) => parseInt(a, 10) - parseInt(b, 10),
  },
  {
    name: "name",
    align: "left ",
    label: "Họ tên",
    field: "name",
    sortable: true,
  },
  {
    name: "birthday",
    align: "left",
    label: "Ngày sinh",
    field: "birthday",
    sortable: false,
    format: (val) => {
      return date.formatDate(val, 'DD/MM/YYYY')
    },
  },
  {
    name: "gender",
    align: "left",
    label: "Giới tính",
    field: "gender",
    sortable: false,
    format: (val) => { return val === 0 ? 'Nam' : 'Nữ' }
  },
  {
    name: "position",
    label: "Chức vụ",
    align: "left",
    field: "position",
    sortable: false,
  },
  {
    name: "job_title",
    label: "Chức danh",
    align: "left",
    field: "job_title",
    sortable: false,
  },
  {
    name: "phone",
    label: "SĐT",
    align: "left",
    field: "phone",
    sortable: true,
  },
  {
    name: "residence_address",
    label: "Địa chỉ",
    align: "left",
    field: "residence_address",
    sortable: false,
  },
  {
    name: "parent_id",
    label: "Quản lý",
    align: "left",
    field: "user_parent",
    sortable: false,
    format: (userParent) => {
        if (isProxy(userParent)) {
          userParent = toRaw(userParent);
        }
        return userParent?.name
    }
  },
  {
    name: "is_active",
    label: "Trạng thái",
    align: "left",
    field: "work_status",
    sortable: true,
    format: (val) => {
      if (val === 1) {
        return 'Đang làm việc';
      }
      if (val === 2) {
        return 'Đã nghỉ việc';
      }
      if (val === 3) {
        return 'Nghỉ tạm thời';
      }
    }
  },
  {
    name: "act",
    label: "Hành động",
    align: "center",
    field: "act",
  },
];

const columnsWorkingProcess = [
  {
    name: "stt",
    label: "STT",
    align: "center",
    field: "stt",
    sortable: false,
  },
  {
    name: "fromdate",
    label: "Từ Ngày",
    align: "center",
    field: "fromdate",
    sortable: false,
  },
  {
    name: "todate",
    label: "Đến Ngày",
    align: "center",
    field: "todate",
    sortable: false,
  },
  {
    name: "unit",
    label: "Đơn Vị",
    align: "center",
    field: "unit",
    sortable: false,
  },
  {
    name: "department_id",
    label: "Phòng Ban",
    align: "center",
    field: "department_id",
    sortable: false,
  },
  {
    name: "part",
    label: "Bộ Phận",
    align: "center",
    field: "part",
    sortable: false,
  },
  {
    name: "position",
    label: "Chức Vụ",
    align: "center",
    field: "position",
    sortable: false,
  },
  {
    name: "decisionnumber",
    label: "Số QĐ",
    align: "center",
    field: "decisionnumber",
    sortable: false,
  },
  {
    name: "act",
    label: "Thao tác",
    align: "center",
    field: "act",
  },
];

const rowsWorkingProcess = ref([
  {
    stt: "1",
    fromdate: "14/01/2023",
    todate: "14/08/2023",
    unit: "Bệnh viện mắt HN 2",
    department: "Khoa Lâm Sàng",
    part: "Khoa Khúc xạ",
    position: "Trưởng Khoa",
    decisionnumber: "QĐ-01",
    act: "",
  },
  {
    stt: "2",
    fromdate: "14/06/2023",
    todate: "14/08/2023",
    unit: "Bệnh viện mắt HN 2",
    department: "Khoa Lâm Sàng",
    part: "Khoa Khúc xạ",
    position: "P.Trưởng Khoa",
    decisionnumber: "QĐ-02",
    act: "",
  },
]);

const columnsSalaryHistory = [
  {
    name: "stt",
    label: "STT",
    align: "center",
    field: "stt",
    sortable: false,
  },
  {
    name: "updateday",
    label: "Ngày Cập Nhật",
    align: "center",
    field: "updateday",
    sortable: false,
  },
  {
    name: "effectivedate",
    label: "Ngày Hiệu Lực",
    align: "center",
    field: "effectivedate",
    sortable: false,
  },
  {
    name: "department",
    label: "Phòng Ban",
    align: "center",
    field: "department",
    sortable: false,
  },
  {
    name: "part",
    label: "Bộ Phận",
    align: "center",
    field: "part",
    sortable: false,
  },
  {
    name: "position",
    label: "Chức Vụ",
    align: "center",
    field: "position",
    sortable: false,
  },
  {
    name: "basicsalary",
    label: "Mức Lương Cơ Bản",
    align: "right",
    field: "basicsalary",
    sortable: false,
  },
  {
    name: "act",
    label: "Thao tác",
    align: "center",
    field: "act",
  },
];

const rowSalaryHistory = ref([
  {
    stt: "1",
    updateday: "14/01/2023",
    effectivedate: "14/08/2023",
    department: "Khoa Lâm Sàng",
    part: "Khoa Khúc xạ",
    position: "Trưởng Khoa",
    basicsalary: "15.000.000",
    act: "",
  },
  {
    stt: "2",
    updateday: "14/06/2023",
    effectivedate: "14/08/2023",
    department: "Khoa Lâm Sàng",
    part: "Khoa Khúc xạ",
    position: "P.Trưởng Khoa",
    basicsalary: "10.000.000",
    act: "",
  },
]);

const columnAllowance = [
  {
    name: "stt",
    label: "STT",
    align: "center",
    field: "stt",
    sortable: false,
  },
  {
    name: "allowance",
    label: "Khoản Phụ Cấp",
    align: "center",
    field: "allowance",
    sortable: false,
  },
  {
    name: "quota",
    label: "Định Mức",
    align: "center",
    field: "quota",
    sortable: false,
  },
  {
    name: "value",
    label: "Giá Trị",
    align: "center",
    field: "value",
    sortable: false,
  },
  {
    name: "status",
    label: "Trạng Thái",
    align: "center",
    field: "status",
    sortable: false,
  },
  {
    name: "act",
    label: "Thao Tác",
    align: "center",
    field: "act",
  },
];

const rowAllowance = ref([
  {
    stt: "1",
    allowance: "Lương trách nhiệm",
    quota: "5.000.000",
    value: "=10%*LUONG_CO_BAN",
    status: "Đang theo dõi",
    act: "",
  },
  {
    stt: "2",
    allowance: "Phụ cấp xăng xe",
    quota: "500.000",
    value: "",
    status: "Đang theo dõi",
    act: "",
  },
]);

const columnsHistorySalary = [
  {
    name: "stt",
    label: "STT",
    align: "center",
    field: "stt",
    sortable: false,
  },
  {
    name: "salaryperiod",
    label: "Kỳ Lương",
    align: "left",
    field: "salaryperiod",
    sortable: false,
  },
  {
    name: "totalsalary",
    label: "Tổng Lương Hưởng",
    align: "left",
    field: "totalsalary",
    sortable: false,
  },
  {
    name: "reduce",
    label: "Giảm Trừ",
    align: "left",
    field: "reduce",
    sortable: false,
  },
  {
    name: "salaryreceived",
    label: "Lương Thực Nhận",
    align: "left",
    field: "salaryreceived",
    sortable: false,
  },
  {
    name: "base",
    label: "Căn Cứ",
    align: "left",
    field: "base",
    classes: "base-style",
    sortable: false,
  },
];

const rowsHistorySalary = ref([
  {
    stt: "1",
    salaryperiod: "06-2023",
    totalsalary: "15.000.000",
    reduce: "700.000",
    salaryreceived: "14.300.000",
    base: "BangluongT6.2023_NV001",
  },
  {
    stt: "2",
    salaryperiod: "05-2023",
    totalsalary: "15.000.000",
    reduce: "500.000",
    salaryreceived: "14.500.000",
    base: "BangluongT5.2023_NV001",
  },
]);

const columnsInfoExtra = [
  {
    name: "stt",
    label: "STT",
    align: "center",
    field: "stt",
    sortable: false,
  },
  {
    name: "salaryperiod",
    label: "Kỳ Lương",
    align: "left",
    field: "salaryperiod",
    sortable: false,
  },
  {
    name: "ewardcontent",
    label: "Nội Dung Khen Thưởng",
    align: "left",
    field: "ewardcontent",
    sortable: false,
  },
  {
    name: "amountmoney",
    label: "Số Tiền",
    align: "left",
    field: "amountmoney",
    sortable: false,
  },
  {
    name: "approved",
    label: "Người Duyệt",
    align: "left",
    field: "approved",
    sortable: false,
  },
  {
    name: "status",
    label: "Trạng Thái",
    align: "left",
    field: "status",
    classes: "status-style",
    sortable: false,
  },
];

const rowsInfoExtra = ref([
  {
    stt: "1",
    salaryperiod: "06-2023",
    ewardcontent: "Nhân viên xuất sắc tháng",
    amountmoney: "1.000.000",
    approved: "Nguyễn Thanh Tùng",
    status: "Đã duyệt",
  },
  {
    stt: "2",
    salaryperiod: "05-2023",
    ewardcontent: "Nhân viên xuất sắc tháng",
    amountmoney: "1.000.000",
    approved: "Nguyễn Thanh Tùng",
    status: "Đã duyệt",
  },
]);

const personalInfors = ref([
  "Mã NV",
  "Họ và tên",
  "Ngày sinh",
  "Giới tính",
  "Số điện thoại",
  "Email",
  "Địa chỉ",
  "Nguyên quán",
  "Số CMND/CCCD",
  "Ngày cấp CMND/CCCD",
  "Nơi cấp CMND/CCCD",
  "Quốc tịch",
  "Dân tộc",
  "Tôn giáo",
  "Tình trạng hôn nhân",
  "Là Đảng viên",
  "Ảnh",
  "Số tài khoản",
  "Ngân hàng",
]);

const worklInfors = ref([
  "Phòng ban",
  "Chức vụ",
  "Chức danh",
  "Mã NV",
  "Quản lý",
  "Phân loại",
  "Trạng thái",
  "Ngày bắt đầu làm việc",
  "Ngày kết thúc làm việc",
  "Nơi công tác trước",
  "Mã HĐ",
  "Loại HĐ",
  "Thời hạn HĐ",
  "Ngày bắt đầu HĐ",
  "Ngày kết thúc HĐ",
  "Mức lương",
  "Mức BHXH",
]);

const dropzoneFile = ref("");
const isPopupDeleteItem = ref(false);
const isPopupDetails = ref(false);
const isDropdownVisible = ref(false);
const tabsDetails = ref("infoPerson");
const employeeId = ref(null);
const isEdit = ref(false);

const {
  rows,
  filter,
  selected,
  isLoading,
  pagination,
  getListData,
  deleteDataItem,
  updateItem,
  createEmployee,
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
  apiEmployee,
  onRequest
} = useEmployee();

const handleOpenPopupDeleteItem = (id) => {
  isPopupDeleteItem.value = true;
  employeeId.value = id;
};

const handleDeleteInfoEmployee = () => {
  deleteDataItem(employeeId.value)
  rows.value = rows.value.filter(
    (item) => item.id !== employeeId.value
  );
  employeeId.value = null;
  isPopupDeleteItem.value = false;
};

const handleDeleteInfoEmployees = () => {
  const ids = [];
  selected.value.map((item) => {
    ids.push(item.id)
  })
  deleteMultiItem(ids)
};

const handleOpenPopupDeleteItems = () => {
  if (selected.value.length > 0) {
    isPopupDeleteItems.value = true;
  } else {
    isPopupSelected.value = true;
  }
};

const handleOpenPopupUpload = () => {
  isPopupUpload.value = true;
};

const handleOpenPopupCreate = () => {
  isPopupCreate.value = true;
  isEdit.value = false;
};

const handleDropZoneFile = (e) => {
  dropzoneFile.value = e;
};

const submitCreateMultiple = async () => {
  await createMultiple(dropzoneFile.value);
}

const handleOpenPopupInfoItem = (id) => {
  isPopupDetails.value = true;
  employee.value = rows.value.find((it) => it.id === id);
};

const handleOpenPopupEditItem = (id) => {
  isEdit.value = true;
  employeeId.value = id;
  isPopupCreate.value = true;
  employee.value = rows.value.find((it) => it.id === id);
};

const handleDownloadExcel = async () => {
  isLoading.value = true;
  let sort;
  sort = {};
  sort[pagination.value.sortBy] = pagination.value.descending;
  try {
    const response = await api.get(`${apiEmployee}/download-excel`, {
      ...config,
      params: {
        page: pagination.value.page,
        per_page: pagination.value.rowsPerPage,
        filter: {...filter.value},
        sort: {...sort},
      },
    });
    if (response.data.code === 200) {
      let data = toRaw(response.data.data);
      if (Array.isArray(data) && data.length > 0) {
        const csvContent =
          Object.keys(data[0]).join(',') + '\n' +
          data.map(row => Object.values(row).join(',')).join('\n');

        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });

        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;

        link.download = 'danh-sach-nhan-su.csv';
        document.body.appendChild(link);
        link.click();

        document.body.removeChild(link);
      } else {
        console.error('Invalid data for CSV download');
      }
    }
  } catch (err) {
    console.log(err)
  }
};

const createData = async (e) => {
  await createEmployee(e)
}

const updateData = async (e) => {
  await updateItem(employeeId.value, e)
  isPopupCreate.value = false;
}
onMounted(() => {
  getListData();
})
</script>

<style lang="scss" scoped>
@import "assets/scss/common.scss";
@import "./assest/EmployeeList.scss";
@import "./assest/popup/EmployeeCreate.scss";
@import "./assest/popup/EmployeeDetails.scss";
</style>
