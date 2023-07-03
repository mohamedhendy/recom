import Vue from 'vue';
import Dialog from 'vue-dialog-loading';
import ToggleButton from 'vue-js-toggle-button';
import VueSimpleAlert from "vue-simple-alert";
import SvgVue from 'svg-vue';
import vmodal from 'vue-js-modal';
import VueLodash from 'vue-lodash'

import Menu from "@/Plugins/Menu"
import App from "@/Plugins/App"
import Form from "@/Plugins/Form"
import CurrencyPlugin from '@/Plugins/Currency';

import 'vue-select/dist/vue-select.css';

import './element';

Vue.use(VueLodash, {lodash: window._})
Vue.use(Dialog, {
    dialogBtnColor: '#0f0',
    background: 'rgba(0, 0, 0, 0.5)'
});
Vue.use(vmodal);
Vue.use(ToggleButton);
Vue.use(VueSimpleAlert);

Vue.use(Menu);
Vue.use(App);
Vue.use(Form);
Vue.use(SvgVue);
Vue.use(CurrencyPlugin);




