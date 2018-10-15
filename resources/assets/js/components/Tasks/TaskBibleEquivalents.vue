<template>
	<div class="container">
	<div class="columns is-multiline">
		<div class="column is-6" v-for="item in equivalents">
			<form class="box" action="/bibles/equivalents" v-on:submit.prevent="onSubmit" v-bind:id="item.equivalent_id">
				<a target="_blank" v-bind:href="item.constructed_url">View Source</a>
				<v-select v-model="selected[item.equivalent_id]" :options='bibles'></v-select>
				<small>{{ item.equivalent_id }}</small>
				<input type="submit" class="button" />
			</form>
		</div>
	</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				equivalents: [],
				bibles: [],
				selected: []
			}
		},
		methods: {

			onSubmit: function() {
				this.loading = true;
				var currentEquivalent = Object.keys(this.selected);
				var bible_id = this.selected[currentEquivalent].value;

				axios.put(apiURL + "bible/equivalents/" + currentEquivalent + apiParams, {
					bible_id: bible_id,
					equivalent_id: currentEquivalent
				})
					.then((response)  =>  {
						this.loading = false;
						this.messages = response.data;
						console.log(this.messages);
					}, (error)  =>  {
						this.loading = false;
					})
			}

		},
		mounted() {
			window.axios.get(apiURL + 'bible/equivalents?key=1234&v=4&bible_id=XXXXXX')
				.then(response => (this.equivalents = response.data))
				.catch(function (error) {
					// handle error
					console.log(error);
				});

			window.axios.get(apiURL + 'bibles?key=1234&v=4')
				.then((response) => {

					var bibles = [];
					_.forEach(response.data.data, function(value,key) {
						if(value['name'] == null) value['name'] = '';
						bibles[key] = {'value': value['abbr'], 'label': value['name']}
					});

					this.bibles = bibles;
				})
				.catch(function (error) {
					// handle error
					console.log(error);
				});

		}
	}
</script>