const emailRules = [
  (val) => !!val || 'Vui lòng không để trống trường này',
  (val) => /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(val) || 'Email không hợp lệ',
];

const passwordRules = [
  (val) => !!val || 'Vui lòng không để trống trường này',
  (val) => val.length >= 6 || 'Vui lòng nhập tối thiểu 6 ký tự',
];

const requireRules = [
  (val) => !!val || 'Vui lòng không để trống trường này',
];
const isValidEmail = (email) => {
  return /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(email);
};

const isValidPassword = (password) => {
  return password.length >= 6;
};

const isValidRequire = (val) => {
  return val.length >= 1;
};

const useValidate = () => {
  return {
    emailRules,
    passwordRules,
    isValidPassword,
    isValidRequire,
    isValidEmail,
    requireRules
  }
}

export { useValidate };
