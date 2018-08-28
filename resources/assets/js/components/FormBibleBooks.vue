<template>
	<div class="form-bible-books columns is-multiline">
		<div class="column is-6" v-for="(bookGroups, index) in books">
			<h2>{{ index }}</h2>
			<label v-for="(book, groupIndex) in bookGroups">
				<input class="checkbox" type="checkbox" :name="book.id" />{{ book.name }}
			</label>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				books: []
			}
		},
		mounted() {
			this.books = JSON.parse(this.$el.dataset.books)
		},
		methods: {

		}
	}
</script>
