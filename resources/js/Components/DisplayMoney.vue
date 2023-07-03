<template>
    <div>{{ getMoney }}</div>
</template>

<script>
import currencyFormatter from "currency-formatter";

export default {
    props: {
        money: {
            required: true
        }
    },
    computed: {
        getMoney() {
            return currencyFormatter.format(this.money, {
                // symbol: '@',
                // decimal: '*',
                // thousand: '^',
                precision: 2,
                code: "EUR",
                format: '%v %s' // %s is the symbol and %v is the value
            });
        }
    }
};
</script>
