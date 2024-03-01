window.getFormData = (el) => {
  return $(el)
    .serializeArray()
    .reduce((res, item) => {
      const v = _.get(res, item.name);
      if (v) {
        if ($.isArray(v)) {
          v.push(item.value);
          _.set(res, item.name, v);
          return res;
        }
        _.set(res, item.name, [v, item.value]);
        return res;
      }
      _.set(res, item.name, item.value);
      return res;
    }, {});
};
