<template>
	<div class="form-bible-translations">
		<div class="column is-2">
			<a v-on:click="addTranslation" class="button">Add Translation</a>
		</div>
		<div v-for="(translation, index) in translations">
			<div class="columns is-multiline">
				<label class="column is-2">Translation <a v-on:click="removeTranslation(index)" class="button has-background-primary has-text-white">Delete</a></label>
				<label class="column is-4">Language <input v-model="translation.language_id" type="text" name="translations[][language_id]" class="input" placeholder="Title"></label>
				<label class="column is-6">Vernacular <input v-model="translation.vernacular" type="checkbox" name="translations[][vernacular]" class="input"></label>
				<label class="column is-6">Name <input v-model="translation.name" type="text" name="translations[][url]" class="input" placeholder="name"></label>
				<label class="column is-6">Description <input v-model="translation.description" type="text" name="translations[][type]" class="input" placeholder="description"></label>
				<label class="column is-6">Notes <input v-model="translation.notes" type="text" name="translations[][notes]" class="input" placeholder="Notes"></label>
			</div>
			<hr />
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				translation: {
					url: '',
					type: ''
				},
				translations: []
			}
		},
		mounted() {
			this.translations = JSON.parse(this.$el.dataset.translations)
		},
		methods: {
			addTranslation: function () {
				this.translations.push(Vue.util.extend({}, this.translation))
			},
			removeTranslation: function (index) {
				Vue.delete(this.translations, index);
			}
		}
	}
</script>
