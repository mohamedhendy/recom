<template>
    <div v-if="!isStock">
        <div class="articles__index__tb-options"
             @mouseleave="hover = false"
             @mouseover="hover = true"
        >
            <ArticleStatusIcon
                :color="color"
                :url="route('sale_orders.update_billing', saleOrderProduct.id)"
            />
            <div :class="[hover ?'articles__index__tb-icon-options__show':'articles__index__tb-icon-options__hide']"
                 class="articles__index__tb-icon-options "
            >
                <inertia-link :href="route('sale_orders.update_billing',saleOrderProduct.id)" class="articles__index__tb-link">
                    {{ $t('billing') }}
                </inertia-link>
            </div>
        </div>
    </div>
</template>

<script>
import ArticleStatusIcon from "@/Components/Article/ArticleStatusIcon";

export default {
    name: "UpdateBillingStatus",
    components: {ArticleStatusIcon},
    props: {

        saleOrderProduct: {
            type: Object,
            required: true
        },
    },
    data() {
        return {
            hover: false
        };
    },
    computed: {
        isStock() {
            if(this.saleOrderProduct.sale_order  && this.saleOrderProduct.sale_order.identity)
            {
                return  this.saleOrderProduct.sale_order.identity.type === 'stock';
            }
            return false;
        },
        color() {
            if (parseInt(this.saleOrderProduct.billed_quantity) >= parseInt(this.saleOrderProduct.quantity))
                return 'green';

            if (parseInt(this.saleOrderProduct.billed_quantity) > 0)
                return 'yellow';

            return 'red';
        }
    },
};
</script>

<style scoped>

</style>
