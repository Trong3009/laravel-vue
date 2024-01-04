<template>
  <div class="block-info">
    <div class="q-container">
      <!-- Start: action list -->
      <div class="row q-mt-lg justify-between" style="position: relative">
        <div class="add__title">Thêm mới vai trò</div>
      </div>
    </div>
  </div>

  <q-form ref="myForm" @submit.prevent="submitForm(roleId)" class="q-gutter-md">
    <div class="" style="padding: 10px 15px">
      <div class="form_group">
        <label>Tên vai trò <span>*</span></label>
        <q-input
          :dense="true"
          outlined
          v-model="role.name"
          lazy-rules
          :rules="requireRules"
        />
      </div>
      <div class="form_group">
        <label>Mô tả vai trò</label>
        <q-input
          type="text"
          :dense="true"
          outlined
          v-model="role.description"
        />
      </div>
    </div>
    <div class="" style="padding: 10px 15px">
      <fieldset>
        <legend>Chọn quyền</legend>
        <div class="role-checkbox">
          <p style="color: #000000; font-weight: 600;">Chức năng</p>
          <div v-if="roleId" class="input-checkbox">
            <label>
              <input class="all-role" type="checkbox" @click="toggleAllPermissions($event)" :checked="allAccountChecked"/>
              Tất cả
            </label>
          </div>
          <div v-else class="input-checkbox">
            <label>
              <input class="all-role" type="checkbox" @click="toggleAllPermissions($event)"/>
              Tất cả
            </label>
          </div>
        </div>

        <div class="role-checkbox permission-group" v-for="(permission, key) in permissions">
          <p>{{ key }}</p>
          <div v-if="roleId" class="input-checkbox">
            <label>
              <input class="all-role-item" type="checkbox"
                     v-model="allAccountItemChecked"
                     @click="toggleGroupPermissions(key, $event)"/>
              Tất cả2
            </label>
          </div>

          <div v-else class="input-checkbox">
            <label>
              <input class="all-role-item" type="checkbox" @click="toggleGroupPermissions(key, $event)"/>
              Tất cả
            </label>
          </div>

          <div v-if="roleId" class="input-checkbox" v-for="item in permission" :key="item.permission_id">
            <label>
              <input class="account permission-id" type="checkbox"
                     v-if="item.slug !== ''"
                     :value="item.permission_id"
                     v-model="permissionIdOfRole"
                     @click="toggleIndividualPermission($event)"
              />
              {{ item.slug }}
            </label>
          </div>
          <div v-else class="input-checkbox" v-for="item in permission" :key="item.permission_id">
            <label>
              <input class="account permission-id" type="checkbox"
                     v-if="item.slug !== ''"
                     :value="item.permission_id"
                     v-model="role.permission_id"
                     @click="toggleIndividualPermission($event)"
              />
              {{ item.slug }}
            </label>
          </div>
        </div>
      </fieldset>
    </div>
    <div style="margin-top: 20px" class="btns-act q-gutter-x-md row justify-center">
      <q-btn
        :to="{name: 'RoleList'}"
        style="background: #cccccc; color: #fff"
        label="Quay lại"
        no-caps
      />
      <q-btn v-if="!roleId"
             style="background: #236674; color: #ffffff"
             label="Lưu lại"
             type="submit"
             no-caps
      />
      <q-btn v-else
             style="background: #236674; color: #ffffff"
             label="Cập nhật"
             no-caps
             type="submit"
      />
    </div>
  </q-form>
</template>

<script>
import { defineComponent } from "vue";
export default defineComponent({
  name: "CreateRole",
});
</script>

<script setup>
import {ref, computed, onMounted} from "vue";
import {useRole} from "src/modules/hrm/composables/useRole";
import {api} from "boot/axios";
import { useValidate } from "src/modules/hrm/composables/useValidate";
import {BASE_URL_API, config} from "src/modules/hrm/constants";
import router from "src/router";
const role = ref({
  name: '',
  description: '',
  permission_id: []
});
const roleId = ref(null);
const {
  apiRole,
  updateItem,
  createRole,
} = useRole();
const {
  requireRules,
} = useValidate();

const permissions = ref([])
const permissionIds = ref([]);
const permissionIdOfRole = ref([]);

const myForm = ref(null);

const toggleAllPermissions = (event) => {
  const isChecked = event.target.checked;
  const groups = permissions.value;
  permissionIds.value = [];
  Object.entries(groups).forEach(function (group) {
    group.forEach(function (items) {
      for (const item of items) {
        if (item.permission_id != null) {
          permissionIds.value.push(item.permission_id);
        }
      }
    })
  });

  if (roleId.value) {
    permissionIdOfRole.value = isChecked
      ? [...new Set([...permissionIdOfRole.value, ...permissionIds.value])]
      : permissionIdOfRole.value.filter(id => !permissionIds.value.includes(id));
  } else {
    role.value.permission_id = isChecked
      ? [...new Set([...role.value.permission_id, ...permissionIds.value])] : [];
  }

  const allCheckboxItems = document.querySelectorAll('.all-role-item');
  allCheckboxItems.forEach(item => {
    item.checked = isChecked;
  });
};

const toggleGroupPermissions = (groupKey, event) => {
  const isChecked = event.target.checked;
  permissionIds.value = [];
  const group = permissions.value[groupKey];
  permissionIds.value = group.map(item => item.permission_id)
  if (roleId.value) {
    permissionIdOfRole.value = isChecked ? [...new Set([...permissionIdOfRole.value, ...permissionIds.value])]
      : permissionIdOfRole.value.filter(id => !permissionIds.value.includes(id));
    const checkboxItems = document.querySelectorAll('.permission-id');
    checkboxItems.forEach(item => {
      item.checked = isChecked
    })
  } else {
    role.value.permission_id = isChecked
      ? [...new Set([...role.value.permission_id, ...permissionIds.value])]
      : role.value.permission_id.filter(id => !permissionIds.value.includes(id));
  }
  const checkAllPermission = document.querySelectorAll('.all-role-item');
  const allAccount = document.querySelector('.all-role');
  allAccount.checked = [...checkAllPermission].every(checkbox => checkbox.checked);
};
const toggleIndividualPermission = (event) => {
  const allAccount = document.querySelector('.all-role');

  const checkboxGroup = event.target.closest('.permission-group').querySelector('.all-role-item');

  const checkboxAll = event.target.closest('fieldset').querySelectorAll('.permission-id');
  const checkboxAllGroup = event.target.closest('.permission-group').querySelectorAll('.permission-id');

  allAccount.checked = [...checkboxAll].every(checkbox => checkbox.checked);
  checkboxGroup.checked = [...checkboxAllGroup].every(checkbox => checkbox.checked);
};

const allAccountItemChecked = computed(() => {
  const groups = permissions.value;
  const permissionIdsAll = [];
  for (const group of Object.values(groups)) {
    for (const item of Object.values(group)) {
      if (item.permission_id != null) {
        permissionIdsAll.push(item.permission_id);
      }
    }
  }
  return permissionIdOfRole.value.every(value => permissionIdsAll.includes(value));
});

const allAccountChecked = computed(() => {
  const groups = permissions.value;
  const permissionIdsAll = [];
  for (const group of Object.values(groups)) {
    for (const item of Object.values(group)) {
      if (item.permission_id != null) {
        permissionIdsAll.push(item.permission_id);
      }
    }
  }
  return allAccountItemChecked.value && permissionIdOfRole.value.every(value => permissionIdsAll.includes(value));
});

const submitForm = (roleId) => {
  if (!roleId) {
    submitCreateRole();
  } else {
    submitUpdateRole();
  }
}

const submitCreateRole = async () => {
  validate().then(async (isValid) => {
    if (isValid) {
      await createRole(role.value)
    } else {
      return Promise.reject('Validation failed');
    }
  }).catch((error) => {
    console.error(error);
  });
}
const submitUpdateRole = async () => {
  role.value.permission_id = permissionIdOfRole.value
  validate().then(async (isValid) => {
    if (isValid) {
      updateItem(roleId.value, role.value)
    } else {
      return Promise.reject('Validation failed');
    }
  }).catch((error) => {
    console.error(error);
  });
}

const validate = async () => {
  const isValid = await myForm.value.validate();
  if (!isValid) {
    alert('Validation failed. Please check the fields.');
  } else if (role.value.permission_id.length === 0) {
    alert('Vui lòng chọn quyền cho vai trò');
    return false;
  }
  return isValid;
}

const getPermission = async () => {
  const response = await api.get(`${BASE_URL_API}/permissions`, null, {
    ...config
  })
  if (response.data.code === 200) {
    permissions.value = response.data.data
  }
}

const getRoleById = async (id) => {
  const response = await api.get(`${apiRole}/${id}`, {...config});
  if (response.data.code === 200) {
    role.value.name = response.data.data.role.name;
    role.value.description = response.data.data.role.description;
    role.value.permission_id = response.data.data.role.permission_id;

    permissions.value = response.data.data.permissions
    permissionIdOfRole.value = response.data.data.role.permissions.map(permission => permission.permission_id);
  }
}

onMounted(() => {
  getPermission();
  if (router.currentRoute.value.params.roleId) {
    roleId.value = router.currentRoute.value.params.roleId;
    getRoleById(roleId.value)
  }
})
</script>
<style lang="scss" scoped>
@import "./assest/popup/CreateRole.scss";
</style>
