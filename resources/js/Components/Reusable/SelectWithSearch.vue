<template>
    <div class="relative w-full">
        <el-select
            v-model="inputValue"
            :disabled="isDisabled"
            filterable
            :placeholder="placeholder"
            class="w-full"
            @change="selectItem"
        >
            <el-option
                v-for="listItem in items"
                :key="listItem.id"
                :label="`${label(listItem)}`"
                :value="listItem.id"
            />
        </el-select>
        <ErrorMessage :error="$page.props.errors.draft_invoice_id"/>
    </div>
</template>

<script>

import ErrorMessage from "@/Components/Reusable/ErrorMessage";

export default {
    components: {ErrorMessage},
    props: {
        isDisabled: {
            type: Boolean,
            default: false,
        },
        items: {
            required: true,
            validator: (value) => {
                return Array.isArray(value) || value === undefined;
            },
        },
        setKey: {
            type: Object,
        },
        optional: {
            type: Boolean,
            default: false,
        },
        placeholder: {
            type: String,
            default: "Select..",
        },
        label: {
            type: Function,
            default: (item) => item
        },
        hasError: {
            type: String,
            default: null,
        },
        errorMessage: {
            type: String,
            default: " ",
        },
        index: {
            default: null,
        },
        // eslint-disable-next-line vue/require-prop-types
        initId: {
            // type: Object,
            default: null,
        },
    },
    data() {
        return {
            selectedItem: {},
            inputValue: "",
            visibility: false,
            filteredItems: [],
        };
    },
    watch: {
        setKey(value) {
            if(value)
                this.selectItem(value.id);
        }
    },

    mounted() {
        if (this.initId) {
            this.selectItem(this.initId);
        }
    },

    methods: {

        selectItem(value) {
            let item = null;
            if(this.items)
                item = this.items.find(p => p.id === value);
            else
                item = null;

            if (item !== null) {
                this.selectedItem = item;
                this.inputValue = this.label(item);
                this.selectedItem = item;
                this.$emit("item-changed", {
                    item: item,
                    index: this.index,
                });
            } else {
                // this.$emit("item-changed", {
                //     item: null,
                //     index: this.index,
                // });
            }
        },


    },
};
</script>

