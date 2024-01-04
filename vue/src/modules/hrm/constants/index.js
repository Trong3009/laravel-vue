import {computed, ref} from "vue";

const BASE_URL_API = "http://127.0.0.1:8000/api";
let isAuthenticated = false;
let token = sessionStorage.getItem('token');

let storedPermissions = sessionStorage.getItem('permissions');
let permissionUserLogin = ref(storedPermissions ? JSON.parse(storedPermissions) : []);

const setAuthToken = (val) => {
  token = val;
  isAuthenticated = !!token;
  sessionStorage.setItem('token', token);
  updateAuthorizationHeader();
};
const getAuthToken = () => token;
const isUserAuthenticated = () => {
  const token = getAuthToken();

  return token !== null;
};

const clearAuthToken = () => {
  token = null;
  isAuthenticated = false;
  sessionStorage.removeItem('token');
  sessionStorage.removeItem('permissions');
};

//update header khi login logout
const updateAuthorizationHeader = () => {
  config.headers.Authorization = `Bearer ${getAuthToken()}`;
};

const savePermissionUserLogin = (permissions) => {
  permissionUserLogin.value = permissions;
  sessionStorage.setItem('permissions', JSON.stringify(permissions));
}
const hasPermission = (permission) => {
  return permissionUserLogin.value && permissionUserLogin.value[permission];
};
const config = {
  headers: {
    Authorization: `Bearer ${getAuthToken()}`,
    'Content-Type': 'application/json',
  },
};

export {
  BASE_URL_API,
  isAuthenticated,
  savePermissionUserLogin,
  isUserAuthenticated,
  setAuthToken,
  clearAuthToken,
  token,
  getAuthToken,
  permissionUserLogin,
  hasPermission,
  config
};
