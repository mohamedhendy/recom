
<template>
    <div class="mb-2 p-2 text-center bg-gray-100 mt-10">
        <div class="flex items-center justify-between gap-5">
            <div class="flex-1">
                <label>{{ $t("total") }}</label>
                <dispaly-money :money="`${total}`"/>
            </div>

            <!--            <div class="flex-1">-->
            <!--                <label>Discount</label>-->
            <!--                <dispaly-money :money="`${discount}`"></dispaly-money>-->
            <!--            </div>-->
            <!--            <div class="flex-1">-->
            <!--                <label>SubTotal</label>-->
            <!--                <dispaly-money :money="`${subtotal}`"></dispaly-money>-->
            <!--            </div>-->
        </div>
    </div>
</template>

<script>
import DispalyMoney from "./DisplayMoney";
require("collections/shim-array");
require("collections/listen/array-changes");
export default {
    name: "OrderTotalAmounts",
    components: { DispalyMoney },
    props: {
        products: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            total: 0,
            subtotal: 0,
            discount: 0,
        };
    },
    watch: {
        products: {
            deep: true,
            handler(v) {
                console.log("updated");
                this.updateData();
            },
        },
    },
    created() {
        this.updateData();
    },
    methods: {
        updateData() {
            let total = 0;
            // subtotal = 0, discount = 0;
            this.products.forEach((item) => {
                total += parseFloat(item.price) * parseFloat(item.quantity);
                // subtotal+=parseFloat(item.subtotal);
                // discount+=parseFloat(item.discount);
            });

            // this.subtotal = subtotal;
            this.total = total;
            // this.discount = discount;
        },
    },
};
</script>

<style>
</style>
