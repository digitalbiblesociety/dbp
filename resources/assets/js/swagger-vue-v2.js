import Vue from 'vue'
import OpenApi from './components/Docs/OpenApi.vue'
import VueResource from 'vue-resource'
import jsonApi from './swagger_v2.json'

Vue.use(VueResource)
Vue.component('bulma-accordion', require('./components/bulma/Accordion.vue'))
Vue.component('bulma-accordion-item', require('./components/bulma/AccordionItem.vue'))


new Vue({
	el: '#app',
	template: '<open-api v-if="jsonApi" :api="jsonApi" :query-params="queryParams" :headers="headers"></open-api>',
	data: () => ({
		jsonApi: jsonApi
	}),
	components: {
		OpenApi
	}
})