<template>
	<div>
		<v-select label="languages" :options="options" @search="onSearch"></v-select>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				options: []
			}
		},
		methods: {
			onSearch(search, loading) {
				loading(true);
				this.search(loading, search, this);
			},
			mounted() {
				this.options = JSON.parse(this.$el.dataset.languages)
			},
			search() {
				//
				window.axios.get(apiURL + 'languages')
				.then(function (response) {
					console.log(response);
					this.options = response;
					//json => (vm.options = json.items);
					loading(false);
				})
				.catch(function (error) {
					// handle error
					console.log(error);
				});
			}
		}
	}
</script>