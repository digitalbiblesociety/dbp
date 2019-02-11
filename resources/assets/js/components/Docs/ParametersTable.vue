<template lang="html">
  <table class="table" v-if="(selectedEntry.parameters && selectedEntry.parameters.length) || selectedEntry.requestBody">
    <thead>
      <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Type</th>
        <th>Values</th>
        <th>Location</th>
        <th>Required</th>
      </tr>
    </thead>

    <tbody>
      <tr v-if="selectedEntry.requestBody">
        <td>Payload</td>
        <td>Request body</td>
        <td v-if="!selectedEntry.requestBody.content"></td>
        <td v-if="selectedEntry.requestBody.content">
			<div class="select">
          		<select v-model="selectedEntry.requestBody.selectedType">
          		  <option v-for="contentType in Object.keys(selectedEntry.requestBody.content)" :key="contentType" :value="contentType">{{contentType}}</option>
          		</select>
			</div>
        </td>
        <td v-if="!selectedEntry.requestBody.content || !selectedEntry.requestBody.content[selectedEntry.requestBody.selectedType].schema"></td>
        <td v-if="selectedEntry.requestBody.content && selectedEntry.requestBody.content[selectedEntry.requestBody.selectedType].schema" style="align-items: left;">
          <button class="button" v-on:click="openSchemaDialog(selectedEntry.requestBody.content[selectedEntry.requestBody.selectedType].schema)">View Schema</button>
        </td>
        <td>body</td>
        <td>
          <input type="checkbox" v-model="selectedEntry.requestBody.required" disabled>
        </td>
      </tr>

      <tr v-for="(parameter, i) in selectedEntry.parameters" :key="i" v-bind:class="{ deprecatedRow: parameter.deprecated }">
        <td>{{parameter.name}} <span class="deprecated" v-if="parameter.deprecated">deprecated</span></td>
        <td v-html="marked(parameter.description)"></td>
        <td v-if="parameter.schema.type !== 'array'">{{parameter.schema.type}}</td>
        <td v-if="parameter.schema.type === 'array'">{{parameter.schema.items.type}} array</td>
        <td v-if="parameter.schema.type !== 'array' && parameter.schema.enum">{{parameter.schema.enum.join(', ')}}</td>
        <td v-if="parameter.schema.type !== 'array' && !parameter.schema.enum"></td>
        <td v-if="parameter.schema.type === 'array'">
          <div style="overflow-y:scroll;max-height:200px;">{{(parameter.schema.items.enum || []).join(', ')}}</div>
        </td>
        <td>{{parameter.in}}</td>
        <td>
          <input type="checkbox" v-model="parameter.required" disabled>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script>
import marked from 'marked'

export default {
  props: [ 'selectedEntry', 'openSchemaDialog', 'openExamplesDialog' ],
  methods: { marked }
}
</script>

<style lang="css">
	.deprecatedRow {
		opacity: .5;
	}

	.deprecated {
		color:red;
		font-weight:bold;
		opacity: 1!important;
	}
</style>
