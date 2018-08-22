<template lang="html">
  <table class="table" style="width: 100%">
    <thead>
      <tr>
        <th>HTTP Code</th>
        <th>Response</th>
        <th>Type</th>
        <th>Schema</th>
        <th>Examples</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(response, code) in selectedEntry.responses" :key="code">
        <td>{{code}}</td>
        <td v-html="marked(response.description)"></td>
        <td v-if="!response.content"></td>
        <td v-if="response.content">
          <select class="select" v-model="response.selectedType">
            <option v-for="(value, content) in response.content" :key="content" :value="content">{{content}}</option>
          </select>
        </td>
        <td v-if="!response.content || !response.content[response.selectedType].schema"></td>
        <td v-if="response.content && response.content[response.selectedType].schema">
          <button class="button" v-on:click="openSchemaDialog(response.content[response.selectedType].schema)">Open</button>
        </td>
        <td v-if="!response.content || !response.content[response.selectedType].examples"></td>
        <td v-if="response.content && response.content[response.selectedType].examples">
          <button class="button" v-on:click="openExamplesDialog(response.content[response.selectedType].examples)">Open</button>
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
</style>
