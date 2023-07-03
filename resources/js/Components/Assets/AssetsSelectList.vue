<template>
    <div class="w-full block">
        <label class="demonstration">{{ $t("asset") }}</label>
        <div>
            <el-select
                v-model="value"
                :disabled="isViewPage"
                :placeholder="$t('asset')"
                class="w-full" filterable
                @change="publish"
            >
                <el-option
                    v-for="item in list"
                    :key="item.id"
                    :label="getLabel(item)"
                    :value="item.id"
                />
            </el-select>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "AssetsSelectList",
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
            this.$emit('change', this.list.find(p => p.id === this.value));
        },
        getList() {
            axios.get(route('api.assets.all')).then(res => {
                this.list = res.data;
            }).finally(() => {
                this.loading = false;

            })
        },
        getLabel(item) {
            let label = item.id + " ";
            if(item.a_number != null && item.a_number !== "" && item.a_number !== " ") {
                label += item.a_number + " ";
            }
            if(item.serial_number != null && item.serial_number !== "" && item.serial_number !== " ") {
                label += item.serial_number + " ";
            }
            if(item.description != null && item.description !== "" && item.description !== " ") {
                label += item.description + " ";
            }

            return label;
        }
    }
}
</script>

<style scoped>

</style>
