import App from "@/Config/app"

export default {
    install(Vue) {
        Vue.prototype.$App = App

        Vue.prototype.$dataset = {
            currencies: [
                "€",
                "$"
            ],
            document_types: [
                'invoice', 'delivery_note', 'cancellation', 'order_confirmation', 'deliver_avis', 'rma', 'other'
            ]
        };
    }
}
