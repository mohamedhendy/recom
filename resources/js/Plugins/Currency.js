import Currencies, {defaultCurrency} from "../Config/currencies";

export default {
	install(Vue) {
		Vue.filter("currency", function (
			value,
			currency = defaultCurrency,
			per_piece = false,
			show_currency = true
		) {
			if (!value) {
				return "";
			}

			let currencyObj = _.find(Currencies, {code: currency});
			value = new Intl.NumberFormat(currencyObj.locale, {
				style: "currency",
				currency: currencyObj.code
			})
				.formatToParts(parseInt(value) / 100)
				.map(({type, value}) => {
					switch (type) {
					case "currency":
						return "";
					default:
						return value;
					}
				})
				.reduce((string, part) => string + part);

			if (show_currency) {
				value = value + " " + currencyObj.symbol;
			}

			if (per_piece) {
				value = value + " / " + i18n.t("per_piece");
			}

			return value.toString().trim();
		});

		Vue.filter("currency_attr", function (currency, attr) {
			return _.find(Currencies, {code: currency})[attr];
		});
	}
};
