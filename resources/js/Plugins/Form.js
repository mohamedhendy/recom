import CustomCurrencyInput from "@/Components/Input/CustomCurrencyInput";
import vSelect from "vue-select";
import VueNumberInput from "@chenfengyuan/vue-number-input";

export default  {
    install(Vue) {
        Vue.component('CustomCurrencyInput', CustomCurrencyInput);
        Vue.component('VSelect', vSelect);
        Vue.component('VueNumberInput', VueNumberInput);
    }
}
