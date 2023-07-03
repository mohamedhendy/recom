export default {

	methods: {


		addDocument(e) {
			let files = e.target.files;
			for (let i = 0; i <= files.length; i++) {
				let file = files[i];
				if (file != null) {
					this.documents.push({
						type: "invoice",
						document: file,
						name: file.name
					});
				}
			}

		},
		async getData() {

			return new Promise((resolve => {

				let formData = new FormData();
				formData.append('internal_id', this.invoiceData.internal_id);
				formData.append('supplier_id', this.invoiceData.supplier_id);
				formData.append('issue_date', this.invoiceData.issue_date);
				formData.append('draft_invoice_id', this.invoiceData.draft_invoice_id);
				formData.append('due_date', this.invoiceData.due_date);
				formData.append('invoice_year', this.invoiceData.invoice_year);


				for (let articleKey in this.invoiceData.articles) {
					let article = this.invoiceData.articles[articleKey];
					formData.append(`articles[${articleKey}][id]`, article.id);
					formData.append(`articles[${articleKey}][product_id]`, article.product_id);
					formData.append(`articles[${articleKey}][description]`, article.description ?? "");
					formData.append(`articles[${articleKey}][cost_price]`, article.cost_price);
					formData.append(`articles[${articleKey}][sales_price]`, article.sales_price);
					formData.append(`articles[${articleKey}][quantity]`, article.quantity);
					formData.append(`articles[${articleKey}][currency_code]`, article.currency_code);


					for (let clientKey in article.article_identities) {
						let customer = article.article_identities[clientKey];

						if (customer.id) {
							formData.append(`articles[${articleKey}][article_identities][${clientKey}][id]`, customer.id);
						}

						formData.append(`articles[${articleKey}][article_identities][${clientKey}][identity_id]`, customer.identity_id);
						formData.append(`articles[${articleKey}][article_identities][${clientKey}][project_id]`, customer.project_id);
						formData.append(`articles[${articleKey}][article_identities][${clientKey}][quantity]`, customer.quantity);
						formData.append(`articles[${articleKey}][article_identities][${clientKey}][type]`, customer.type);


						let salesPrice = customer.sales_price;
						if (customer.identity && customer.identity.type === 'stock') {
							if (isNaN(parseInt(salesPrice)))
								salesPrice = 0;
							else
								salesPrice = parseInt(salesPrice);

						}
						formData.append(`articles[${articleKey}][article_identities][${clientKey}][sales_price]`, salesPrice);
						formData.append(`articles[${articleKey}][article_identities][${clientKey}][description]`, customer.description ?? "");

						if (customer.deployments) {
							for (let deploymentKey in customer.deployments) {
								let deployment = customer.deployments[deploymentKey];

								formData.append(`articles[${articleKey}][article_identities][${clientKey}][deployments][${deploymentKey}][id]`, deployment.id ?? 0);
								formData.append(`articles[${articleKey}][article_identities][${clientKey}][deployments][${deploymentKey}][a_number]`, deployment.a_number ?? "");
								formData.append(`articles[${articleKey}][article_identities][${clientKey}][deployments][${deploymentKey}][serial_number]`, deployment.serial_number ?? "");
								formData.append(`articles[${articleKey}][article_identities][${clientKey}][deployments][${deploymentKey}][description]`, deployment.description ?? "");

							}
						}


					}


					for (let documentKey in article.documents) {
						let document = article.documents[documentKey];

						if (document.id !== undefined) {
							formData.append(`articles[${articleKey}][documents][${documentKey}][id]`, document.id);
						}
						formData.append(`articles[${articleKey}][documents][${documentKey}][document]`, document.document);
						formData.append(`articles[${articleKey}][documents][${documentKey}][type]`, document.type);
					}
				}


				resolve(formData);

			}));

		},
	}
};
