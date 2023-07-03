export default {
	created() {
		window.addEventListener("keyup", (e) => {
			if (
				(e.code === "Escape" || e.code === "escape" || e.keyCode === 27) &&
				!this.closing
			) {
				this.closing = true;
				this.confirmClosePage();
			}
		});
	},
	methods: {

		confirmClosePage() {


			this.$confirm({
				message: this.$t("are_you_sure"),
				button: {
					no: this.$t("no"),
					yes: this.$t("yes"),
				},
				/**
				 * Callback Function
				 * @param {Boolean} confirm
				 */
				callback: (confirm) => {
					if (confirm) {
						location.href = '/articles';
					}

					this.closing = false;
				},
			});
		}
	}
};
