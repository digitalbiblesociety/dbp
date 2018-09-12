<template lang="html">
  <form novalidate @submit.stop.prevent="submit" v-if="selectedEntry" id="request-form">
    <div v-if="selectedEntry.requestBody">
      <label class="label" for="payload">Payload ({{selectedEntry.requestBody.selectedType}})</label>
      <textarea name="payload" v-model="currentRequest.body"></textarea>
    </div>

<div class="columns is-multiline">
    <div class="column is-6-desktop" v-for="(parameter, i) in selectedEntry.parameters" :key="i">

      <div v-if="(parameter.schema.type === 'string' || parameter.schema.type === 'integer' || parameter.schema.type === 'number') && !parameter.schema.enum">
        <label class="label">{{parameter.name}} <small class="is-4 has-text-primary has-text-right">{{parameter.schema.type}}</small></label>
        <input class="input" v-model="currentRequest.params[parameter.name]" :type="parameter.schema.type === 'string' ? 'text' : 'number'">
      </div>

      <div v-if="parameter.schema.enum">
        <label class="label">{{parameter.name}}</label>
        <select class="select" v-model="currentRequest.params[parameter.name]">
          <option v-for="val in parameter.schema.enum" :key="val" :value="val">{{val}}</option>
        </select>
      </div>

      <div v-if="parameter.schema.type === 'array' && parameter.schema.items.enum">
        <label class="label">{{parameter.name}}</label>
        <div class="select is-rounded">
        	<select v-model="currentRequest.params[parameter.name]">
        	  <option v-for="val in parameter.schema.items.enum" :key="val" :value="val">{{val}}</option>
        	</select>
        </div>
      </div>

	<label v-if="parameter.schema.type === 'boolean'">
		<input type="checkbox" v-model="currentRequest.params[parameter.name]">{{parameter.name}}
	</label>

    </div>
</div>
  </form>
</template>

<script>
export default {
  props: ['selectedEntry', 'currentRequest']
}
</script>