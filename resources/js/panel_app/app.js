import api from './api';

require('./bootstrap');
require('./components/form');
import router from './components/router';
import formater from './utils/formater';

const App = (() => {
  return {
    api,
    formater,
    setRoutes: router.setRoutes,
    route: router.route,
  };
})();

export default App;

window.PanelApp = App;
