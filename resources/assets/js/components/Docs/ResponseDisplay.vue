<template lang="html">
  <div class="response-display">
    <span>{{entry.method.toUpperCase()}} {{response.url}}&nbsp;&nbsp;&nbsp;&nbsp;<span :class="response.ok ? 'md-primary':'md-warn'">{{response.status}} {{response.statusText}}</span></span>
    <br>

    <ul class="md-dense">
      <li>
        <strong style="padding-left:0px;">Headers</strong>
        <div class="expand">
          <ul>
            <li v-for="header in Object.keys(response.headers.map)">{{header}}: {{Array.isArray(response.headers.map[header]) ? response.headers.map[header].join(',') : response.headers.map[header]}}</li>
          </ul>
        </div>
      </li>
    </ul>
    <h4>Body</h4>
    <pre>{{formattedBody}}</pre>
  </div>
</template>

<script>
export default {
  props: ['response', 'entry'],
  computed: {
    formattedBody() {
      let res
      try {
        res = JSON.stringify(this.response.body, null, 2)
        if (typeof res === 'string') res = this.response.body
      } catch (err) {
        res = this.response.body
      }
      return res
    }
  }
}
</script>

<style lang="css">
.response-display pre {
  white-space: pre-wrap;
}
</style>
