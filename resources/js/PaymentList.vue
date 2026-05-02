<template>
  <Menu />
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
      <tr>
        <th>flg</th>
        <th>id</th>
        <th>from</th>
        <th></th>
        <th>to</th>
        <th>item</th>
        <th></th>
        <th>amount</th>
        <th></th>
      </tr>
      <template v-for="group in groupedPayments" :key="group.key">
        <!-- 外側ループ: groupedPayments の各グループ（from_user|to_user ペア）をループ
             例: group.key = "Aさん|Bさん", "Bさん|Cさん" など -->
        <tr :style="{ backgroundColor: '#f0f0f0', fontWeight: 'bold' }">
          <!-- サマリ行: グループごとの集計情報を表示 -->
          <td></td>
          <td colspan="2">{{ group.from_user }}</td>
          <td>→</td>
          <td>{{ group.to_user }}</td>
          <td>合計</td>
          <td></td>
          <td>{{ group.total }}</td>
          <td>円 ({{ group.items.length }}件)</td>
        </tr>
        <!-- 内側ループ: 各グループ内の支払い詳細行（group.items）をループ
             例: group.items = [{id:1, ...}, {id:2, ...}] -->
        <tr v-for="p in group.items" :key="p.id">
          <!-- 詳細行: 個別の支払い情報を表示 -->
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
      </template>
    </table>
  </div>
</template>

<script>
import axios from "axios";
import Menu from './components/Menu.vue';

export default {
  components: {
    Menu
  },
  data() {
    return {
      payments: [],
      selectedIds: [],
      filter: 'unprocessed'
    };
  },

  computed: {
    groupedPayments() {
      // 1. payments を from_user, to_user でソート
      const sorted = [...this.payments].sort((a, b) => {
        if (a.from_user !== b.from_user) {
          return a.from_user.localeCompare(b.from_user);
        }
        return a.to_user.localeCompare(b.to_user);
      });

      // 2. from_user|to_user のペアごとにグループ化
      const groups = {};
      sorted.forEach(p => {
        // group.key の値: "Aさん|Bさん", "Bさん|Cさん" など
        const pair = `${p.from_user}|${p.to_user}`;
        if (!groups[pair]) {
          groups[pair] = {
            key: pair,  // group.key = "Aさん|Bさん" のような形式
            from_user: p.from_user,
            to_user: p.to_user,
            items: [],  // このグループに属する支払い
            total: 0    // このグループの合計金額
          };
        }
        groups[pair].items.push(p);
        groups[pair].total += p.amount;
      });

      // 3. オブジェクトから配列に変換して返却
      // 返却形式: [{key: "Aさん|Bさん", from_user: "Aさん", to_user: "Bさん", items: [...], total: 5000}, ...]
      return Object.values(groups);
    }
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
