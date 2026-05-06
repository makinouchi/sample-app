<template>
  <div class="menu">
    <ul>
      <li v-for="item in menus" :key="item.name">
        <a :href="item.path">{{ item.name }}</a>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  data() {
    return {
      menus: [
        { name: '一覧', path: '/payments' },
        { name: '登録', path: '/' },
        { name: 'G一覧', path: '/sheets' },
        { name: 'テスト', path: '/commands' },
      ]
    }
  }
}
</script>

<style scoped>
.menu ul {
  display: flex;
  list-style: none;
  padding: 0;
  margin: 0;
  border-bottom: 2px solid #ccc;
}

.menu li {
  margin-right: 10px;
}

.menu a {
  text-decoration: none;
  color: #333;
  padding: 8px 16px;
  border: 1px solid #ccc;
  border-bottom: none;
  border-radius: 5px 5px 0 0;
  background-color: #f5f5f5;
  display: block;
}

.menu a:hover {
  background-color: #e0e0e0;
}

.menu a.active {
  background-color: white;
  border-bottom: 2px solid white;
  font-weight: bold;
}
</style>
