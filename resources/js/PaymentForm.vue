<template>
  <div>
    <h2>支払い登録</h2>

    <div>
      <label>処理済み</label>
      <input type="checkbox" v-model="is_processed">
    </div>

    <div>
      <label>内容</label>
      <input v-model="description">
    </div>

    <div>
      <label>誰から</label>
      <input v-model="from_user">
    </div>

    <div>
      <label>誰に</label>
      <input v-model="to_user">
    </div>

    <div>
      <label>金額</label>
      <input v-model="amount" type="number">
    </div>

    <div>
      <label>登録日</label>
      <input v-model="registered_at" type="date">
    </div>

    <button @click="save">登録</button>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    return {
      from_user: '',
      to_user: '',
      amount: '',
      registered_at: '',
      description: '',
      is_processed: false
    }
  },
  methods: {
    async save() {
      await axios.post('/payments', {
        from_user: this.from_user,
        to_user: this.to_user,
        amount: this.amount,
        registered_at: this.registered_at,
        is_processed: this.is_processed,
        description: this.description
      })

      alert('登録されました')
    }
  }
}
</script>
