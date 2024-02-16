window.getFormData = (el) => {
  return $(el)
    .serializeArray()
    .reduce((res, item) => {
      _.set(res, item.name, item.value);
      return res;
    }, {});
};
