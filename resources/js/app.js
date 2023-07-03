/* global route */

import "./bootstrap";

import "moment";
import Vue from 'vue';
import {InertiaApp} from '@inertiajs/inertia-vue';
import {InertiaForm} from 'laravel-jetstream';
import PortalVue from 'portal-vue';
import PageMixin from './Mixins/PageMixin';
import Base from "./base"
import "./plugins";

Vue.mixin({methods: {route}});
Vue.mixin(Base);
Vue.use(InertiaApp);
Vue.use(InertiaForm);
Vue.use(PortalVue);

const app = document.getElementById("app");

new Vue({
    render: h =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: name => import(`./Pages/${name}`)
                    .then(({default: page}) => {
                        if(page)
                        {
                            let mixins = Array.from(page.mixins ?? []);
                            mixins.push(PageMixin);
                            page.mixins = mixins;
                        }

                        return page
                    }),
            },
        }),
}).$mount(app);
