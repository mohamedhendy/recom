<template>
    <div class="w-full block">
        <label class="demonstration">{{ _.get($page.props, 'screen.labels.choose_client', $t('customers')) }}</label>
        <div>
            <!--            _.get($page.props. 'screen.labels.choose_client', $t('customers'))-->
            <el-select v-model="value" :disabled="isViewPage" :placeholder="$t('customers')" class="w-full" filterable
                       @change="publish"
            >
                <el-option v-for="item in list" :key="item.id" :label="item.name" :value="item.id"/>
            </el-select>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "CustomersSelectList",
    props: {
        cleanAfterSelect: {
            type: Boolean,
            default: true,
        },
        isViewPage: {
            type: Boolean,
            default: false,
        },
        baseValue: {}
    },
    data() {
        return {
            loading: false,
            list: [],
            value: ""
        }
    },
    created() {
        this.value = this.baseValue;
        this.getList();
    },
    methods: {
        publish() {
            this.$emit('input', this.value);
            this.$emit('change', this.list.find(p => p.id === this.value));
            if(this.cleanAfterSelect)
                this.value = ""
        },
        getList() {
            axios.get(route('api.customers.all')).then(res => {
                this.list = res.data;
            }).finally(() => {
                this.loading = false;

            })
        }
    }
}
</script>

<style scoped>

</style>
