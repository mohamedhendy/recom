<template>
    <div>
        <button
            class="flex items-center px-4 py-2 space-x-4 text-white bg-gray-900 rounded hover:bg-gray-800 focus:outline-none"
            type="button"
            @click="openCreateProductPop"
        >
            <svg-vue icon="save" class="w-5 h-5"/>
            <span class="text-sm">{{ $t("new_product") }}</span>
        </button>
        <!-- ,{ dynamicDefault: { draggable: true, resizable: true ,adaptive: true} } -->
        <div class="relative">
            <modal :adaptive="false"
                   :height="'99%'"
                   :width="'99%'" name="createProductModeal"
            >
                <form-vue :return-created="true" @created="productCreated"/>
            </modal>
        </div>
    </div>
</template>

<script>
import FormVue from '../../Pages/Products/Form.vue';

export default {
    components: {FormVue},
    props: {
        index: {
            type: Number
        }
    },
    methods: {
        openCreateProductPop() {
            this.$modal.show('createProductModeal');
        },
        productCreated(e) {
            this.$emit("created", {product: e.product, index: this.index});
            this.$modal.hide('createProductModeal');
        }
    },
};
</script>

<style>
</style>
