import Vue from 'vue'
import VueMaterial from 'vue-material'
import OpenApi from './docs/OpenApi.vue'
import '../sass/components/vue-material.css'
import VueResource from 'vue-resource'

import jsonApi from './swagger_v2-gen.json'

Vue.use(VueMaterial)
Vue.use(VueResource)

new Vue({
	el: '#app',
	template: '<open-api v-if="jsonApi" :api="jsonApi" md-theme="\'default\'" :query-params="queryParams" :headers="headers"></open-api>',
	data: () => ({
		jsonApi: jsonApi
	}),
	components: {
		OpenApi
	}
})