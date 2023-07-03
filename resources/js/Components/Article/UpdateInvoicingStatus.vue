<template>
    <div>
        <div class="articles__index__tb-options"
             @mouseleave="hover = false"
             @mouseover="hover = true"
        >
            <ArticleStatusIcon
                :color="color"
                :url="url"
            />
            <div :class="[hover ?'articles__index__tb-icon-options__show':'articles__index__tb-icon-options__hide']"
                 class="articles__index__tb-icon-options"
            >
                <inertia-link :href="url"
                              class="articles__index__tb-link"
                >
                    {{ $t('add_documents') }}
                </inertia-link>
            </div>
        </div>
    </div>
</template>

<script>
import ArticleStatusIcon from "@/Components/Article/ArticleStatusIcon";

export default {
    name: "UpdateInvoicingStatus",
    components: {ArticleStatusIcon},
    props: {
        url: {
            required:true,

        },
        orderProduct: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            hover: false
        };
    },
    computed: {
        color() {

            let hasInvoice = this.orderProduct.documents.find(p => p.type === 'invoice');
            if (hasInvoice)
                return 'green';


            if (this.orderProduct.documents.length > 0)
                return 'yellow';

            return 'red';
        }
    },
};
</script>

<style scoped>

</style>
