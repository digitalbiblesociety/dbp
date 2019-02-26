<template>
	<div class="openapi">
		<section class="hero is-primary">
			<div class="hero-body">
				<div class="container">
					<h1 class="title">{{ api.info.title }}</h1>
					<small v-if="api.info.version">{{ api.info.version }}</small>
				</div>
			</div>
		</section>

			<div class="container box">
						<div class="columns">
							<div class="column is-one-quarter">
								<bulma-accordion :dropdown="true" :icon="'caret'" id="docs-nav">
									<div v-for="(entries, tag) in tags" :key="tag">
										<bulma-accordion-item>
											<h4 slot="title">{{ tag }}</h4>
											<div slot="content">
												<ul class="menu-list">
													<li>
														<a :class="{'has-text-primary':selectedEntry === entry}" v-for="(entry, i) in entries" :key="i" v-on:click="select(entry)" style="display: block">
															<span v-bind:class="{entry: entry.method}">{{ entry.method }}</span>
															<b>{{ entry.summary }}</b>
															<small v-html="entry.path.replace(/\//g,'<b>/</b>')"></small>
														</a>
													</li>
												</ul>
											</div>
										</bulma-accordion-item>
									</div>
								</bulma-accordion>
							</div>
							<div class="column" v-if="!selectedEntry">
								<p>Select an entry on the left to see detailed information...</p>
							</div>
							<div class="column is-three-quarters" v-if="selectedEntry">

								<h2 class="is-size-3 has-text-centered">{{selectedEntry.title || selectedEntry.summary}}</h2>
								<p class="has-text-centered" v-if="selectedEntry.description" v-html="marked(selectedEntry.description)"></p>
								<h3 class="has-text-centered"><b class="has-text-primary">{{selectedEntry.method.toUpperCase()}}</b> {{api.servers[0].url + selectedEntry.path}}</h3>
								<tabs animation="slide" :only-fade="false">
									<tab-pane label="Responses">
										<h4 v-if="(selectedEntry.parameters && selectedEntry.parameters.length) || selectedEntry.requestBody">Parameters</h4>
										<parameters-table :selectedEntry="selectedEntry" :openSchemaDialog="openSchemaDialog" :openExamplesDialog="openExamplesDialog"></parameters-table>
										<responses-table :selectedEntry="selectedEntry" :openSchemaDialog="openSchemaDialog" :openExamplesDialog="openExamplesDialog"></responses-table>
										<modal ref="schemaDialog" :visible="false" :closable="true" transition="zoom">
											<schema-view :schema="currentSchema"></schema-view>
											<pre>{{ JSON.stringify(currentSchema, null, 2)}}</pre>
										</modal>
										<modal ref="examplesDialog" :visible="false" :closable="true" transition="zoom">
											<schema-view :schema="currentExamples"></schema-view>
											<pre>{{ JSON.stringify(currentExamples, null, 2)}}</pre>
										</modal>
									</tab-pane>
									<tab-pane label="Request">
										<request-form :selectedEntry="selectedEntry" :currentRequest="currentRequest"></request-form>
										<button class="button" v-on:click="request">Execute</button>
										<response-display v-if="currentResponse" :entry="selectedEntry" :response="currentResponse"></response-display>
									</tab-pane>
								</tabs>

							</div>

						</div>
			</div>
	</div>
</template>

<script>
	import marked from 'marked'
	import RequestForm from './RequestForm.vue'
	import ResponseDisplay from './ResponseDisplay.vue'
	import ResponsesTable from './ResponsesTable.vue'
	import ParametersTable from './ParametersTable.vue'
	import SchemaView from './SchemaView.vue'

	import { Collapse, Item as CollapseItem } from 'vue-bulma-collapse'
	import { Tabs, TabPane } from 'vue-bulma-tabs'
	import { Modal } from 'vue-bulma-modal'

	export default {
		name: 'open-api',
		components: {
			RequestForm,
			ResponseDisplay,
			ResponsesTable,
			ParametersTable,
			SchemaView,
			Collapse,
			CollapseItem,
			Tabs,
			TabPane,
			Modal
		},
		props: ['api', 'headers', 'queryParams'],
		data: () => ({
			selectedEntry: null,
			currentSchema: ' ',
			currentExamples: [],
			currentRequest: {
				contentType: '',
				body: '',
				params: {}
			},
			currentResponse: null
		}),
		computed: {
			tags: function() {
				return getTag(this.api)
			}
		},
		methods: {
			marked,
			reset(entry) {
				const newParams = {};
				(entry.parameters || []).forEach(p => {
					this.currentRequest.params[p.name] = (p.in === 'query' && this.queryParams && this.queryParams[p.name]) || (p.in === 'header' && this.headers && this.headers[p.name]) || null
					if (!newParams[p.name]) {
						if (p.schema && p.schema.enum) newParams[p.name] = p.schema.enum[0]
						if (p.schema && p.schema.type === 'array') newParams[p.name] = []
						if (p.example) newParams[p.name] = p.example
					}
				})
				this.currentRequest.params = newParams
				if (entry.requestBody) {
					this.currentRequest.contentType = entry.requestBody.selectedType
					const example = entry.requestBody.content[this.currentRequest.contentType].example
					this.currentRequest.body = typeof example === 'string' ? example : JSON.stringify(example, null, 2)
				}
			},
			select(entry) {
				this.reset(entry)
				this.selectedEntry = entry
			},
			openSchemaDialog(schema) {
				this.currentSchema = schema
				this.$refs.schemaDialog.show = true;
			},
			openExamplesDialog(examples) {
				this.currentExamples = examples
				this.$refs.examplesDialog.show = true
			},
			request() {
				this.currentResponse = null
				fetch(this.currentRequest, this.selectedEntry, this.api).then(res => {
					this.currentResponse = res
				}, res => {
					this.currentResponse = res
				})
			}
		}
	}

	/*
	 * HTTP requests utils
	 */

	function fetch(request, entry, api) {
		let params = Object.assign({}, ...(entry.parameters || [])
			.filter(p => p.in === 'query' && (p.schema.type === 'array' ? request.params[p.name].length : request.params[p.name]))
			.map(p => ({
				// TODO : join character for array should depend of p.style
				[p.name]: p.schema.type === 'array' ? request.params[p.name].join(',') : request.params[p.name]
			}))
		)
		let headers = Object.assign({}, ...(entry.parameters || [])
			.filter(p => p.in === 'header' && (p.schema.type === 'array' ? request.params[p.name].length : request.params[p.name]))
			.map(p => ({
				// TODO : join character for array should depend of p.style
				[p.name]: p.schema.type === 'array' ? request.params[p.name].join(',') : request.params[p.name]
			}))
		)
		const httpRequest = {
			method: entry.method,
			url: api.servers[0].url + entry.path.replace(/{(\w*)}/g, (m, key) => {
				return request.params[key]
			}),
			params,
			headers
		}
		if (entry.requestBody) {
			httpRequest.headers['Content-type'] = entry.requestBody.selectedType
			httpRequest.body = request.body
		}
		return Vue.http(httpRequest)
	}

	/*
	 * Tags management utils
	 */

	import deref from 'json-schema-deref-local'

	const defaultStyle = {
		query: 'form',
		path: 'simple',
		header: 'simple',
		cookie: 'form'
	}

	function processContent(contentType, api) {
		// Spec allow examples as an item or an array. In the API or in the schema
		// we always fall back on an array
		if (contentType.schema) {
			contentType.examples = contentType.examples || contentType.schema.examples
			contentType.example = contentType.example || contentType.schema.example
		}

		if (contentType.example) {
			contentType.examples = [contentType.example]
		}
	}

	function getTag(api) {
		const derefAPI = deref(api)

		var tags = {}
		Object.keys(derefAPI.paths).forEach(function(path) {
			Object.keys(derefAPI.paths[path])
				.filter(function (method) {
					return ['get', 'put', 'post', 'delete', 'options', 'head', 'patch', 'trace'].indexOf(method.toLowerCase()) !== -1
				})
				.forEach(function(method) {
					let entry = derefAPI.paths[path][method]
					entry.method = method
					entry.path = path
					// Filling tags entries
					entry.tags = entry.tags || []
					if (!entry.tags.length) {
						entry.tags.push('No category')
					}
					entry.tags.forEach(function(tag) {
						tags[tag] = tags[tag] || []
						tags[tag].push(entry)
					})

					entry.parameters = entry.parameters || []
					if (derefAPI.paths[path].parameters) {
						entry.parameters = derefAPI.paths[path].parameters.concat(entry.parameters)
					}
					if (entry.parameters) {
						entry.parameters.forEach(p => {
							p.style = p.style || defaultStyle[p.in]
							p.explode = p.explode || (p.style === 'form')
							p.schema = p.schema || { type: 'string' }
						})
					}
					if (entry.requestBody) {
						if (entry.requestBody.content) {
							Vue.set(entry.requestBody, 'selectedType', Object.keys(entry.requestBody.content)[0])
							entry.requestBody.required = true
							Object.values(entry.requestBody.content).forEach(contentType => processContent(contentType, api))
						}
					}

					// Some preprocessing with responses
					entry.responses = entry.responses || {}
					Object.values(entry.responses).forEach(response => {
						if (response.content) {
							// preselecting responses mime-type
							Vue.set(response, 'selectedType', Object.keys(response.content)[0])
							Object.values(response.content).forEach(contentType => processContent(contentType, api))
						}
					})
				})
		})
		return tags
	}
</script>
