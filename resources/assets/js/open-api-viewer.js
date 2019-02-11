import Vue from 'vue'
import OpenApi from './components/Docs/OpenApi.vue'
import VueResource from 'vue-resource'

Vue.use(VueResource)
Vue.component('bulma-accordion', require('./components/bulma/Accordion.vue'))
Vue.component('bulma-accordion-item', require('./components/bulma/AccordionItem.vue'))


new Vue({
	el: '#app',
	template: '<open-api v-if="jsonApi" :api="jsonApi" :query-params="queryParams" :headers="headers"></open-api>',
	mounted () {
		axios
			.get('/open-api-'+ window.location.pathname.split("/").pop() +'.json')
			.then(response => (this.jsonApi = response.data))
	},
	data() {
		return {
			queryParams: [],
			headers: [],
			jsonApi: []
		}
	},
	components: {
		OpenApi
	}
})