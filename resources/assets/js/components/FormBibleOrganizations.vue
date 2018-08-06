<template>
	<div class="form-bible-organization">
		<div class="col-md-2">
			<button type="button" v-on:click="addOrganization" class="btn btn-block btn-success">Add Organization</button>
		</div>
		<div v-for="(organization, index) in organization">
			<div class="row">
				<label class="col-md-2">&nbsp; <button type="button" v-on:click="removeOrganization(index)" class="btn btn-block btn-danger">Remove -</button></label>
				<v-select label="name" :options='allOrganizations'></v-select>
				<!-- <label class="col-md-4">Organization <input v-model="organization.language_id" type="text" name="organization[][language_id]" class="form-control" placeholder="Title"></label> -->
				<label class="col-md-6">Vernacular <input v-model="organization.vernacular" type="checkbox" name="organization[][vernacular]" class="form-control"></label>
			</div>
			<hr />
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				organization: {
					language_id: '',
					vernacular: ''
				},
				organizations: [],
				allOrganizations: [],
			}
		},
		mounted() {
			this.organizations = JSON.parse(this.$el.dataset.organizations)
			this.allOrganizations = JSON.parse(this.$el.dataset.allOrganizations)
		},
		methods: {
			addOrganization: function () {
				this.organizations.push(Vue.util.extend({}, this.organization))
			},
			removeOrganization: function (index) {
				Vue.delete(this.organizations, index);
			}
		}
	}
</script>
