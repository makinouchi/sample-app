<template>
  <div>
    <h2>支払い一覧</h2>

    <div style="margin-bottom:8px;">
      <label>表示: </label>
      <select v-model="filter" @change="fetchPayments">
        <option value="all">全て</option>
        <option value="processed">処理済みのみ</option>
        <option value="unprocessed">処理済み以外</option>
      </select>
    </div>

    <table border="1">
      <tr v-for="p in payments" :key="p.id">
        <td>
          <input
            type="checkbox"
            :value="p.id"
            :checked="p.is_processed || selectedIds.includes(p.id)"
            @change="onCheckboxChange($event, p)">
        </td>
        <td>[{{ p.id }}]</td>
        <td>{{ p.from_user }}</td>
        <td>→</td>
        <td>{{ p.to_user }}</td>
        <td>{{ p.description }}</td>
        <td>のため</td>
        <td>{{ p.amount }}</td>
        <td>円を払う</td>
      </tr>
    </table>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      payments: [],
      selectedIds: [],
      filter: 'unprocessed'
    };
  },

  mounted() {
    this.fetchPayments();
  },

  methods: {
    async fetchPayments() {
      const res = await axios.get("/api/payments", { params: { filter: this.filter } });
      this.payments = res.data;
      // ensure selectedIds only contains ids still present and not processed
      this.selectedIds = this.selectedIds.filter(id => this.payments.some(p => p.id === id && !p.is_processed));
    },

    async onCheckboxChange(e, p) {
      const newValue = e.target.checked;
      const id = p.id;
      try {
        await axios.put(`/api/payments/${id}`, { is_processed: newValue });
        // remove from list if the new state doesn't match current filter
        if (this.filter === 'unprocessed' && newValue === true) {
          this.payments = this.payments.filter(x => x.id !== id);
        } else if (this.filter === 'processed' && newValue === false) {
          this.payments = this.payments.filter(x => x.id !== id);
        } else {
          const idx = this.payments.findIndex(x => x.id === id);
          if (idx !== -1) this.payments[idx].is_processed = newValue;
        }
        // keep selectedIds consistent (remove if processed)
        this.selectedIds = this.selectedIds.filter(x => this.payments.some(p => p.id === x && !p.is_processed));
      } catch (err) {
        console.error(err);
        alert('更新に失敗しました');
        await this.fetchPayments();
      }
    },


    async markSelected() {
      if (this.selectedIds.length === 0) return;
      try {
        const res = await axios.post("/api/payments/mark-processed", { ids: this.selectedIds });
        const count = res.data && res.data.count ? res.data.count : 0;
        // If current filter is 'unprocessed', remove updated items locally so they disappear immediately.
        if (count > 0 && this.filter === 'unprocessed') {
          this.payments = this.payments.filter(p => !this.selectedIds.includes(p.id));
        } else {
          // otherwise refresh the list to reflect changes
          await this.fetchPayments();
        }
        this.selectedIds = [];
      } catch (err) {
        console.error(err);
        alert('更新に失敗しました');
      }
    }
  }
};
</script>
