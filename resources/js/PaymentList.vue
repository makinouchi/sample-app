<template>
  <div>
    <h2>支払い一覧</h2>

    <table border="1">
      <tr>
        <th>id</th>
        <th>誰から→</th>
        <th>誰に</th>
        <th>～のため</th>
        <th>〇円払う</th>
        <th>処理済みフラグ</th>
      </tr>
      <tr v-for="p in payments" :key="p.id">
        <td>{{ p.id }}</td>
        <td>{{ p.from_user }}</td>
        <td>{{ p.to_user }}</td>
        <td>{{ p.description }}</td>
        <td>{{ p.amount }}</td>
        <td>{{ p.is_processed }}</td>
      </tr>
    </table>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      payments: []
    };
  },

  mounted() {
    this.fetchPayments();
  },

  methods: {
    async fetchPayments() {
      const res = await axios.get("/api/payments");
      this.payments = res.data;
    }
  }
};
</script>
