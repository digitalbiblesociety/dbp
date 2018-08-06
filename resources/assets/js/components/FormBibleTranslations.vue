<template>
	<div class="form-bible-translations">
		<div class="col-md-2">
			<button type="button" v-on:click="addTranslation" class="btn btn-block btn-success">Add Translation</button>
		</div>
		<div v-for="(translation, index) in translations">
			<div class="row">
				<label class="col-md-2">&nbsp; <button type="button" v-on:click="removeTranslation(index)" class="btn btn-block btn-danger">Remove -</button></label>
				<label class="col-md-4">Language <input v-model="translation.language_id" type="text" name="translations[][language_id]" class="form-control" placeholder="Title"></label>
				<label class="col-md-6">Vernacular <input v-model="translation.vernacular" type="checkbox" name="translations[][vernacular]" class="form-control"></label>
			</div>
			<div class="row">
				<label class="col-md-6">Name <input v-model="translation.name" type="text" name="translations[][url]" class="form-control" placeholder="name"></label>
				<label class="col-md-6">Description <input v-model="translation.description" type="text" name="translations[][type]" class="form-control" placeholder="description"></label>
				<label class="col-md-6">Notes <input v-model="translation.notes" type="text" name="translations[][notes]" class="form-control" placeholder="Notes"></label>
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
