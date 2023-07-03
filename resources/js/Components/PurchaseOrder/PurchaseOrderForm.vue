<template>
    <div class="">
        <div class="form">
            <div class="flex gap-6">
                <div class="flex-1">
                    <suppliers-select-list v-model="form.supplier_id" :is-view-page="isViewPage"
                                           :base-value="form.supplier_id"
                    />
                    <ErrorMessage :error="$page.props.errors[`supplier_id`]"/>
                </div>
                <div class="block">
                    <span class="demonstration">{{ $t('issue_date') }}</span>
                    <div class="w-full">
                        <el-date-picker v-model="form.issue_date" :disabled="isViewPage" type="date" class="w-full"
                                        :placeholder="$t('issue_date')"
                        />
                        <ErrorMessage :error="$page.props.errors[`issue_date`]"/>
                    </div>
                </div>


                <div class="block">
                    <span class="demonstration">{{ $t('estimated_delivery_date') }}</span>
                    <div class="w-full">
                        <el-date-picker v-model="form.due_date" :disabled="isViewPage" type="date" class="w-full"
                                        :placeholder="$t('estimated_delivery_date')"
                        />
                        <ErrorMessage :error="$page.props.errors[`estimated_delivery_date`]"/>
                    </div>
                </div>
                <div class="block">
                    <span class="demonstration">{{ $t('internal_id') }}</span>
                    <div class="w-full">
                        <el-input v-model="form.internal_id" :disabled="isViewPage" class="w-full"
                                  :placeholder="$t('internal_id')"
                        />
                        <ErrorMessage :error="$page.props.errors[`internal_id`]"/>
                    </div>
                </div>

                <YearField v-model="form.invoice_year" :label="$t('invoice_year')" name="invoice_year"
                           :disabled="isViewPage" filterable :placeholder="$t('invoice_year')"
                />
            </div>

            <order-total-amounts :products="form.products"/>
            <div class="w-11/12 mx-auto mt-10">
                <purchase-order-products v-model="form.products" :customers-staffs-list="dataSource.customerStaff"
                                         :entities="form.products"
                                         :is-view-page="isViewPage"
                />
            </div>
        </div>
    </div>
</template>

<script>
import SuppliersSelectList from "@/Components/Supplier/SuppliersSelectList";
import PurchaseOrderProducts from "@/Components/PurchaseOrder/PurchaseOrderProducts";
import OrderTotalAmounts from "@/Components/OrderTotalAmounts";
import ErrorMessage from "@/Components/Reusable/ErrorMessage";
import YearField from "@/Components/Form/Fields/YearField";

export default {
    name: "PurchaseOrderForm",
    components: {YearField, ErrorMessage, OrderTotalAmounts, PurchaseOrderProducts, SuppliersSelectList},
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
            dataSource: {
                customerStaff: []
            },
            form: {
                id: null,
                invoice_year: "2021",
                internal_id: "",
                supplier_id: "",
                issue_date: new Date().toISOString().substr(0, 10),
                due_date: new Date(new Date().setDate(new Date().getDate() + 3))
                    .toISOString()
                    .substr(0, 10),
                products: [],
            }
        };
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
        this.loadCustomersAndStaffs();
        if (this.entity) {
            this.form.id = this.entity.id;
            this.form.supplier_id = this.entity.supplier_id;
            this.form.internal_id = this.entity.internal_id;
            this.form.products = this.entity.purchase_order_products;
            this.form.due_date = this.entity.due_date;
            this.form.issue_date = this.entity.issue_date;
            this.form.invoice_year = this.entity.creation_year;
        }

    },
    methods: {
        loadCustomersAndStaffs() {
            let list  = [];
            Promise.all([axios.get(route('api.customers.all')), axios.get(route('api.staffs.all'))]).then((values) => {
                const customers = values[0].data;
                const staffs = values[1].data;
                customers.forEach((item) => {
                    item.item_type = 'customer';
                    list.push(item);
                })

                staffs.forEach((item) => {
                    item.item_type = 'staff';
                    list.push(item);
                })
                this.dataSource.customerStaff = list;
            })
        }
    }
};
</script>

<style scoped>

</style>
