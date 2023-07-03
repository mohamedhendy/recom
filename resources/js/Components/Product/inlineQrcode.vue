<template>
    <div v-if="product" class="flex items-center flex-col  flex items-center justify-center">
        <div :id="refId">
            <vue-qrcode
                :ref="refId"
                :quality="1"
                :scale="8"
                :value="getQrCode()"
            />
        </div>
        <button
            class="flex items-center px-2 py-1 space-x-4 text-white bg-gray-900 rounded hover:bg-gray-800 focus:outline-none"
            type="button"
            @click="printQrCode"
        >
            <svg-vue icon="save" class="w-3 h-3"/>
            <span class="text-xs">{{ $t("print") }}</span>
        </button>
    </div>
</template>

<script>
import VueQrcode from 'vue-qrcode';


export default {
    components: {
        VueQrcode
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
        printQrCode() {

            let mywindow = window.open('', 'PRINT', 'height=650,width=900,top=100,left=150');
            mywindow.document.write(`<html><head><title>${this.$t("qr_code")}</title>`);
            mywindow.document.write('</head><body >');
            mywindow.document.write(document.getElementById(this.refId).innerHTML);
            mywindow.document.write('</body></html>');
            mywindow.document.close();
            mywindow.focus();

            mywindow.print();

            mywindow.onafterprint = function () {
                mywindow.close();
            };


        },
        getQrCode() {
            let qrcodeDetail = {
                product_internal_id: this.product.id,
                ean_number: this.product.ean_number ?? "",
                product_name: this.product.name ?? "",
                entity: "product",
                generated_via: 'webapp',
                // printed_at: (new Date).toString()
            };

            if (this.deployment && this.deployment.id) {
                qrcodeDetail.serial_number = this.deployment.serial_number ?? "";
                qrcodeDetail.a_number = this.deployment.a_number ?? "";
                qrcodeDetail.deployment_internal_id = this.deployment.id;
            }

            return JSON.stringify(qrcodeDetail);
        },
    }
};
</script>

