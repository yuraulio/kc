let router = {};

const setRoutes = (list) => {
  router = $.extend(true, {}, router, list);
};

const route = (name) => {
  return router[name];
};

export default { setRoutes, route };
