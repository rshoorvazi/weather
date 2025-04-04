import './bootstrap';
import './clock.js';
import './chart.js';

import.meta.glob([

    '../images/**',

    '../fonts/**',

]);
import axios from 'axios';

window.axios = axios;

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
