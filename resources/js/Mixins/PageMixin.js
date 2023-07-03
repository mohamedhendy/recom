import Alert from './AlertMixin';
import Response from './ResponseMixin';

export default {
    mixins: [Alert, Response],
    created() {
        if (this.pageTitle) {
            this.setPageTitle(this.pageTitle);
        }
    },
    methods: {
        setPageTitle(title) {
            document.title = title;
        },
    }
}
