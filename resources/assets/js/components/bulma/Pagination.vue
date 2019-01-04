<template>
	<nav :class="getNavClassName()">
		<router-link class="pagination-previous" :to="urlBuilder(prevPage)" :disabled="outOfRegion(formatCurrentPage - 1)">{{prev}}</router-link>
		<router-link class="pagination-next" :to="urlBuilder(nextPage)"  :disabled="outOfRegion(formatCurrentPage + 1)">{{next}}</router-link>
		<ul class="pagination-list" >
			<li v-for="item in pagingList" >
				<router-link v-if="item !== '...'" :class="getPagingClassName(item)" :to="urlBuilder(item)">{{ item }}</router-link>
				<span v-else class="pagination-ellipsis">...</span>
			</li>
		</ul>
	</nav>
</template>
<script>
	import paging from './paging.js'
	export default {
		name: 'vue-bulma-pagination',
		props: {
			urlPrefix: {
				type:String,
				default:'/'
			},
			urlBuilder: {
				type: Function,
				default (page) {
					return this.normalize(`${this.urlPrefix}/${page}`)
				}
			},
			currentPage: {
				type: Number,
				default: 1
			},
			lastPage: Number,
			displayPage: {
				type: Number,
				default: 4
			},
			modifiers: {
				type: String,
				default: ''
			},
			prev: {
				type: String,
				default: 'Prev'
			},
			next: {
				type: String,
				default: 'Next'
			},
		},
		methods: {
			getNavClassName () {
				var optional = ['','is-centered','is-right']
				if(['','is-centered','is-right'].indexOf(this.modifiers.trim()) >= 0){
					return 'pagination ' + this.modifiers
				} else {
					console.warn(" modifiers %s is not within the options ", this.modifiers, optional,
						'\n see more detail https://github.com/vue-bulma/vue-bulma-pagination#doc')
					return 'pagination'
				}
			},
			getPagingClassName (item) {
				return this.currentPage !== item ? 'pagination-link' : 'pagination-link is-current'
			},
			outOfRegion (page) {
				return page < 1 || page > this.lastPage
			},
			normalize (path) {
				return path.replace(/\/+/g,'/')
			}
		},
		computed: {
			pagingList () {
				return paging(this.currentPage, this.lastPage, this.displayPage)
			},
			formatCurrentPage () {
				const currentPage = Number(this.currentPage)
				return currentPage > 0 ? currentPage : 1
			},
			prevPage () {
				return Math.max(this.formatCurrentPage - 1, 1)
			},
			nextPage () {
				return Math.min(this.formatCurrentPage + 1, this.lastPage)
			}
		}
	}
</script>

<style >
	.pagination-list {
		list-style : none ;
	}
	.pagination-list li {
		list-style : none ;
	}
</style>