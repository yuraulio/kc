export default {
  price(n, c = '€') {
    return c + ' ' + Number(n).toLocaleString();
  },
};
