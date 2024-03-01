import router from '../components/router';

const prepareHeaders = (headers) => {
  let defaultHeaders = {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    Authorization: 'Bearer ' + $('meta[name="api-token"]').attr('content'),
  };
  return $.extend(true, {}, defaultHeaders, headers);
};

export default {
  post(route, data, success, error) {
    axios
      .post(router.route(route), JSON.stringify(data), {
        headers: prepareHeaders({
          Accept: 'application/json',
          'Content-Type': 'application/json',
        }),
      })
      .then((response) => {
        success(response.data, response);
      })
      .catch((e) => {
        if (error) {
          error(e);
        }
      });
  },
};
