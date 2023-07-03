<template>
    <div class="">
        <div class="form">
            <div class="flex gap-6">
                <customers-staffs-select-list
                    class="flex-1"
                    :clean-after-select="false"
                    :is-view-page="isViewPage"
                    :base-value-id="form.identity_id"
                    :base-value-type="form.identity_type"
                    :error="$page.props.errors[`identity_id`]"
                    @change="identityChanged"
                />

                <div class="block">
                    <span class="demonstration">{{ $t('issue_date') }}</span>
                    <div class="w-full">
                        <el-date-picker
                            v-model="form.issue_date"
                            :disabled="isViewPage"
                            type="date"
                            class="w-full"
                            :placeholder="$t('issue_date')"
                        />
                        <ErrorMessage :error="$page.props.errors[`issue_date`]"/>
                    </div>
                </div>


                <div class="block">
                    <span class="demonstration">{{ $t('estimated_delivery_date') }}</span>
                    <div class="w-full">
                        <el-date-picker
                            v-model="form.due_date"
                            :disabled="isViewPage"
                            type="date"
                            class="w-full"
                            :placeholder="$t('estimated_delivery_date')"
                        />
                        <ErrorMessage :error="$page.props.errors[`estimated_delivery_date`]"/>
                    </div>
                </div>
                <div class="block">
                    <span class="demonstration">{{ $t('internal_id') }}</span>
                    <div class="w-full">
                        <el-input
                            v-model="form.internal_id"
                            :disabled="isViewPage"
                            class="w-full"
                            :placeholder="$t('internal_id')"
                        />
                        <ErrorMessage :error="$page.props.errors[`internal_id`]"/>
                    </div>
                </div>
            </div>

            <order-total-amounts :products="form.products"/>
            <div class="w-5/6 mx-auto mt-10">
                <sale-order-products
                    v-model="form.products"
                    :entities="form.products"
                    :is-view-page="isViewPage"
                />
            </div>
        </div>
    </div>
</template>

<script>
import CustomersSelectList from "@/Components/Customer/CustomersSelectList";
import SaleOrderProducts from "@/Components/SaleOrder/SaleOrderProducts";
import OrderTotalAmounts from "@/Components/OrderTotalAmounts";
import ErrorMessage from "@/Components/Reusable/ErrorMessage";
import CustomersStaffsSelectList from "@/Components/Identity/CustomersStaffsSelectList";
export default {
    name: "PurchaseOrderForm",
    components: {CustomersStaffsSelectList, ErrorMessage, OrderTotalAmounts, SaleOrderProducts, CustomersSelectList},
    props: {
        isViewPage: {
            type: Boolean,
            default: false,
        },
        entity: {
            type: Object,
        },
        activePage: {
            type: String,
            default: ""
        }
    },
    data() {
        return {
            form: {
                identity_data: {},
                invoice_year: "2021",
                internal_id: "",
                identity_id: "",
                identity_type: "",
                issue_date: new Date().toISOString().substr(0, 10),
                due_date: new Date(new Date().setDate(new Date().getDate() + 3))
                    .toISOString()
                    .substr(0, 10),
                products: [],
            }
        }
    },
    watch: {
        form: {
            deep: true,
            handler(value) {
                this.$emit("input", value);
            },
        },
    },
    created() {
        if (this.entity) {
            this.form.identity_id = this.entity.identity_id;
            this.form.identity_type = this.entity.type;
            this.form.internal_id = this.entity.internal_id;
            this.form.products = this.entity.sale_order_products;
            this.form.due_date = this.entity.due_date;
            this.form.issue_date = this.entity.issue_date;
            this.form.invoice_year = this.entity.invoice_year;
        }

    },
    methods: {
        identityChanged(data) {
            this.form.identity_type = data.type;
            this.form.identity_id = data.item.id;
        }
    },
}
</script>

<style scoped>

</style>
