<template>
  <q-layout>
    <q-page-container>
      <q-page class="flex bg-image flex-center">
        <q-card v-bind:style="$q.screen.lt.sm?{'width': '80%'}:{'width':'30%'}">
          <q-card-section>
            <q-avatar size="103px" class="absolute-center shadow-10">
              <img src="profile.svg">
            </q-avatar>
          </q-card-section>
          <q-card-section>
            <div class="text-center q-pt-lg">
              <div class="col text-h6 ellipsis">
                Log in
              </div>
            </div>
          </q-card-section>
          <q-card-section>
            <q-form
              class="q-gutter-md"
              ref="formLogin"
              @submit.prevent="submitLogin"
            >
              <q-input
                filled
                v-model="user.user_name"
                label="Username"
                lazy-rules
                :rules="requireRules"
              />

              <q-input
                type="password"
                filled
                v-model="user.password"
                label="Password"
                lazy-rules
                :rules="passwordRules"
              />
              <div>
                <q-btn label="Login" type="submit" color="primary"/>
              </div>
            </q-form>
          </q-card-section>
        </q-card>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script>
import {defineComponent} from 'vue'

export default defineComponent({
  name: "Login",
});
</script>

<script setup>
import {ref} from 'vue'
import { useValidate} from "src/modules/hrm/composables/useValidate";
import {api} from "boot/axios";
import { setAuthToken, BASE_URL_API, token, savePermissionUserLogin} from "src/modules/hrm/constants";
import router from "src/router";

const formLogin = ref(null)
const user = ref({
  user_name: '',
  password: ''
});

const {
  passwordRules,
  requireRules
} = useValidate();

const submitLogin = async () => {
  try {
    const isValid = await validate();
    if (isValid) {
      const response = await api.post(`${BASE_URL_API}/login`, user.value);
      if (response.data.authorization.token !== '' && response.data.authorization.type === 'bearer') {
        setAuthToken(response.data.authorization.token)
        savePermissionUserLogin(response.data.permissions);
        await router.push('/');
      } else {
        alert(response.data.error);
      }
    } else {
      return Promise.reject('Validation failed');
    }
  } catch (error) {
    alert('Tài khoản hoặc mật khẩu đăng nhập không chính xác vui lòng kiểm tra lại');
  }
};

const validate = async () => {
  const isValid = await formLogin.value.validate();
  if (!isValid) {
    alert('Validation failed. Please check the fields.');
  }
  return isValid;
}
</script>

<style>

.bg-image {
  background-image: linear-gradient(135deg, #7028e4 0%, #e5b2ca 100%);
}
</style>
