<template>
  <Menu />
  <div>
    <h2>Googleシート一覧</h2>
    <a href="https://docs.google.com/forms/d/e/1FAIpQLSeBero8QsPMmeu1TeXhaS2-FPOI51M9q5j6FhhAtvCZ_gsd_g/viewform?usp=publish-editor">登録(google form)</a>、
    <a href="https://docs.google.com/spreadsheets/d/1uHySGl-eSUzvkKlEiDpvsxfA0B2MG8iRC-1kaIBGOeg/edit?gid=1936111498#gid=1936111498">スプレッドシート</a>

    <table v-if="rows.length" border=1>
      <tr v-for="(row, i) in rows" :key="i">
        <template v-for="(cell, j) in row" :key="j">
          <th v-if="i === 0">{{ cell }}</th>
          <td v-else>{{ cell }}</td>
        </template>
      </tr>
    </table>

    <p v-else>データなし</p>
  </div>
</template>

<script>
import Menu from './components/Menu.vue';

export default {
  components: {
    Menu
  },
  data() {
    return {
      rows: [],
      loading: true
    }
  },
  mounted() {
    fetch('/api/sheets')
      .then(res => res.json())
      .then(data => {
        this.rows = data.values || []
      })
      .catch(err => {
        console.error(err)
      })
      .finally(() => {
        this.loading = false
      })
  }
}
</script>
