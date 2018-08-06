<template>
	<div class="form-bible-links">
		<div class="col-md-2">
			<button type="button" v-on:click="addLink" class="btn btn-block btn-success">Add Link</button>
		</div>
		<div v-for="(link, index) in links">
			<div class="row">
				<label class="col-md-2">&nbsp; <button type="button" v-on:click="removeLink(index)" class="btn btn-block btn-danger">Remove -</button></label>
				<label class="col-md-4">Title <input v-model="link.title" type="text" name="links[][title]" class="form-control" placeholder="Title"></label>
				<label class="col-md-6">Type <input v-model="link.type" type="text" name="links[][type]" class="form-control" placeholder="Type"></label>
			</div>
			<div class="row">
				<label class="col-md-6">Url <input v-model="link.url" type="text" name="links[][url]" class="form-control" placeholder="url"></label>
				<label class="col-md-6">Organization <input v-model="link.organization" type="text" name="links[][type]" class="form-control" placeholder="Organization"></label>
			</div>
			<hr />
		</div>
	</div>
</template>

<script>
    export default {
	    data() {
			return {
				link: {
					url: '',
					type: ''
				},
		    	links: []
			}
	    },
	    mounted() {
		    this.links = JSON.parse(this.$el.dataset.links)
	    },
		methods: {
			addLink: function () {
				this.links.push(Vue.util.extend({}, this.link))
			},
			removeLink: function (index) {
				Vue.delete(this.links, index);
			}
		}
    }
</script>
