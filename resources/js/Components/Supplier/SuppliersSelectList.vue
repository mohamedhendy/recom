<template>
    <div class="w-full block">
        <label class="demonstration">{{ $t('supplier') }}</label>
        <div>
            <el-select v-model="value" :disabled="isViewPage" filterable :placeholder="$t('supplier')" class="w-full"
                       @change="publish"
            >
                <el-option
                    v-for="item in list"
                    :key="item.id"
                    :label="item.full_name"
                    :value="item.id"
                />
            </el-select>
        </div>
    </div>
</template>

<script>
export default {
    name: "SuppliersSelectList",
    props: {
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
        },
        getList() {
            axios.get(route('api.suppliers.all')).then(res => {
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
