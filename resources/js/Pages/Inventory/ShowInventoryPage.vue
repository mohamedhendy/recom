<template>
    <app-layout>
        <template #header>
            <h2 class="screen__title">
                {{ $t("inventory") }}
            </h2>
        </template>

        <div class="screen__content w-11/12 mx-auto">
            <inventory-products :is-view-page="true" :entities="$page.props.entity.inventory_products"/>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import ResponseMixin from "@/Mixins/ResponseMixin";
import InventoryProducts from "@/Components/Inventory/InventoryProducts";

export default {
    name: "ShowInventoryPage",
    components: {InventoryProducts, AppLayout},
    mixins: [ResponseMixin],
    data() {
        return {
            formData: {}
        }
    },
}
</script>

<style scoped>

</style>
