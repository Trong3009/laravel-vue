
export const checkInputRequire = (value) => {
    if (value) return true;
    return false;
}

export const checkCheckboxRequire = (arrayValue) => {
    return arrayValue.includes(true);
}