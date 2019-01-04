<template>
	<open-api v-if="jsonApi" :api="jsonApi" :query-params="queryParams" :headers="headers"></open-api>
</template>
<script>
	export default {
		mounted() {
			axios.get('/swagger_docs')
				.then((response)  =>  {
					this.jsonApi = response.data;
				}, (error)  =>  {
					this.loading = false;
				})
		},
		data() {
			return {
				jsonApi: [],
				queryParams: null,
				headers: null
			}
		}
	}
</script>

<style >
</style>