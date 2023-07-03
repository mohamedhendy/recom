<template>
    <div>
        <el-input
            v-if="!disabled"
            v-model="inputValue"
            v-currency="config"
            :disabled="disabled"
            :label="title"
            :placeholder="title"
            class="text-center"
            suffix-icon="el-icon-money"
            @change="publish"
        />
        <div v-else>
            <display-money :money="inputValue"/>
        </div>
        <ErrorMessage
            :error="error"
        />
    </div>
</template>

<script>
import { CurrencyDirective, setValue } from "vue-currency-input";
import DisplayMoney from "./DisplayMoney";
import ErrorMessage from "@/Components/Reusable/ErrorMessage";

export default {
    directives: {
        currency: CurrencyDirective,
    },
    components: { DisplayMoney, ErrorMessage },
    props: {
        value: {},
        disabled: {
            type: Boolean,
            default: false,
        },
        init: {
            // type: Number,
        },
        title: {
            default: "",
        },
        error: {
            default: ""
        },
    },
    data: () => ({
        inputValue: 0,
        config: {
            locale: "en",
            currency: "EUR",
            valueAsInteger: false,
            distractionFree: true,
            precision: 2,
            autoDecimalMode: true,
            valueRange: { min: 0 },
            allowNegative: false,
        },
    }),
    watch: {
        value: {
            handler(val) {
                this.inputValue = parseFloat(val).toFixed(2);
            },
        },
    },
    created() {
        // this.config.currency = 'EUR';
        this.inputValue = parseFloat(this.value).toFixed(2);
    },
    methods: {
        publish() {
            this.$emit("input", this.inputValue);
            this.$emit("change", this.inputValue);
        },
    },
};
</script>

<style>
</style>


<style scoped>

</style>
