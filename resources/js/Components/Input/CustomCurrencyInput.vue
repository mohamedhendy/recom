<template>
    <div>
        <label>
            {{ placeholder }}
            <span v-if="required === true" class="text-red-400">*</span>
        </label>

        <!--        <input-->
        <!--            v-model="valueLocal"-->
        <!--            :class="{'border-red-500':error}"-->
        <!--            :disabled="disabled" :placeholder="placeholder" class="w-full" type="text"-->
        <!--        >-->
        <CurrencyField
            v-model="valueLocal"
            :disabled="disabled"
            :class="{'border-red-500':error}"
            class="w-full"
            :title="placeholder"
            :error="error"
        />
    </div>
</template>
<script>
import CurrencyField from "@/Components/CurrencyField";

export default {
    name: "CustomCurrencyInput",
    components: {CurrencyField},
    model: {prop: "value", event: "input"},
    props: {
        currency: {
            type: String,
            default: "$"
        },
        error: {
            type: String,
        },
        disabled: {
            type: Boolean,
            default: false
        },
        placeholder: {
            type: String
        },
        value: {
            type: [String, Number],
            default: "0"
        },
        required: {
            type: Boolean,
            default: false
        }
    },

    computed: {
        valueLocal: {
            get() {
                return this.value;
                // return this.$options.filters.currency(
                //     this.value,
                //     "EUR",
                //     false,
                //     false
                // );
            },
            set(value) {
                // value = value.replace(/(\.|,)/g, "").replace(/^0+/, "");
                this.$emit("input", value);
            }
        }
    }

};
</script>
<!--v-model="article.cost_price"-->
<style scoped>

</style>
