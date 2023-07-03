export default {
	methods: {

		dragover(event) {
			event.preventDefault();
			// Add some visual fluff to show the user can drop its files
			if (!event.currentTarget.classList.contains('bg-gray-200')) {
				event.currentTarget.classList.remove('bg-gray-100');
				event.currentTarget.classList.add('bg-gray-200');
			}
		},
		dragleave(event) {
			// Clean up
			event.currentTarget.classList.add('bg-gray-100');
			event.currentTarget.classList.remove('bg-gray-200');
		},
		drop(event) {
			event.preventDefault();
			let files = event.dataTransfer.files;
			this.addDocuments(event, files); // Trigger the onChange event manually
			// Clean up
			event.currentTarget.classList.add('bg-gray-100');
			event.currentTarget.classList.remove('bg-gray-200');
		},
		deleteDocument(index) {
			this.documents.splice(index, 1);
		},

		addDocuments(e, files = null) {
			if (this.documentType) {
				files = files == null ? e.target.files : files;
				for (let i = 0; i <= files.length; i++) {
					let file = files[i];
					if (file != null) {
						this.documents.push({
							type: this.documentType,
							document: file,
							name: file.name
						});
					}
				}
			} else {
				this.$alert(this.$t('select_document_type_first'), '', 'error');
			}


			// this.clients.push(this.defaultClient);
		},
	}
};
