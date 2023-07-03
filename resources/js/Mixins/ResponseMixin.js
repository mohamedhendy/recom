import AlertMixin from './AlertMixin';
import {Inertia} from "@inertiajs/inertia";

export default {
    components: {AlertMixin},
    methods: {
        handleResponse(url = null, title = "Success", message = "Operation Completed", useResponseId = false,type = 'success') {
            Inertia.on('invalid', (e) => {
                const statusCode = e.detail.response.status;
                e.preventDefault();
                if (statusCode === 500) {
                    this.alertUser("Server Error", e.detail.response.statusText, "error");
                } else if (statusCode === 404) {
                    this.alertUser("Request Error", e.detail.response.statusText, "warning");
                } else if (statusCode === 403) {
                    this.alertUser("Permssion Denied", "Operation Not Allowed", "warning");
                } else {
                    this.alertUser(title, message, type).then(() => {
                        if(useResponseId && e.detail.response.data.id && url) url = url + '/' + e.detail.response.data.id;
                        if (url)
                            Inertia.visit(url);
                    })
                }
            })


        },
    }
}
