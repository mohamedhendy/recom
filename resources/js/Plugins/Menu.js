import Menu from "@/Config/menu.js"

export default {
    install(Vue) {
        Vue.prototype.$menuConfig = Menu
    }
}
