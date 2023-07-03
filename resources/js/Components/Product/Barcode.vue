<template>
    <div class="flex items-center flex-col">
        <h3 class="text-xl">
            barcode
        </h3>
        <div :id="refId">
            <!-- format="EAN13" -->
            <barcode :value="getBarcode()">
                Show this if the rendering fails.
            </barcode>
        </div>
        <button
            class="flex items-center px-4 py-2 space-x-4 text-white bg-gray-900 rounded hover:bg-gray-800 focus:outline-none"
            type="button"
            @click="printQrCode"
        >
            <svg-vue icon="save" class="w-5 h-5"/>
            <span class="text-sm">{{ $t("print") }}</span>
        </button>
    </div>
</template>

<script>
import VueBarcode from "vue-barcode";

export default {
    components: {barcode: VueBarcode},
    props: {
        product: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            refId: `product_bar_code_ref_${this.product.id}`,
        };
    },
    methods: {
        printQrCode() {
            const printContents = document.getElementById(this.refId).innerHTML;
            const originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        },
        getBarcode() {
            return this.product.ean_number;
            // return JSON.stringify({
            //     id: this.product.id,
            //     ean_number: this.product.ean_number,
            //     qr_code_version: 1,
            //     entity: "product",
            //     generated_via: "webapp",
            //     printed_at: new Date().toString(),
            // });
        },
    },
};
</script>

<style>
</style>
