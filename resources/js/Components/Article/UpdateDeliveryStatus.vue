<template>
    <div class="">
        <div class="articles__index__tb-options"
             @mouseleave="hover = false"
             @mouseover="hover = true"
        >
            <ArticleStatusIcon
                :color="color"
                :url="route('purchase_orders.update_delivery_status',[purchaseOrderProduct.id,saleOrderProduct.id])"
            />
            <div :class="[hover ?'articles__index__tb-icon-options__show':'articles__index__tb-icon-options__hide']"
                 class="articles__index__tb-icon-options "
            >
                <inertia-link :href="route('purchase_orders.update_delivery_status',[purchaseOrderProduct.id,saleOrderProduct.id])"
                              class="articles__index__tb-link"
                >
                    {{ $t('update_delivery_status') }}
                </inertia-link>
            </div>
        </div>
    </div>
</template>

<script>
import ArticleStatusIcon from "@/Components/Article/ArticleStatusIcon";

export default {
    name: "UpdateDeliveryStatus",
    components: {ArticleStatusIcon},
    props: {
        purchaseOrderProduct: {
            type: Object,
            required: true
        },saleOrderProduct: {
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
            if (parseFloat(this.saleOrderProduct.delivered_quantity) >= parseFloat(this.saleOrderProduct.quantity))
                return 'green';

            if (parseFloat(this.saleOrderProduct.delivered_quantity) > 0)
                return 'yellow';

            return 'red';
        }
    },

};
</script>

<style scoped>

</style>


