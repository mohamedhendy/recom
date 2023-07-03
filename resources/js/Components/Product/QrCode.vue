<template>
    <div v-if="product" class="w-full">
        <modal :adaptive="true" :max-height="700" :min-height="700" :name="refId" :resizable="true">
            <inline-qrcode :deployment="deployment" :product="product"/>
        </modal>

        <button
            class="flex items-center px-2 py-1 space-x-4 text-white bg-gray-900 rounded hover:bg-gray-800 focus:outline-none"
            type="button"
            @click="showQrCode"
        >
            <svg-vue icon="save" class="w-3 h-3"/>
            <span class="text-xs">{{ $t("show_qr_code") }}</span>
        </button>
    </div>
</template>

<script>


import InlineQrcode from "@/Components/Product/inlineQrcode";

export default {
    components: {
        InlineQrcode
    },
    props: {
        product: {
            type: Object,
            default: () => null
        },
        deployment: {
            type: Object,
            default: () => null
        }
    },
    data() {
        return {
            refId: `product_qr_code_ref_${this.product ? this.product.id : ""}_${this.deployment ? this.deployment.id : ""}`
        };
    },

    methods: {
        showQrCode() {
            this.$modal.show(this.refId);
        },

    }
};
</script>

<style>
</style>
