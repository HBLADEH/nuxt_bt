<template>
  <div>
    <AdminNavbar></AdminNavbar>
    <b-breadcrumb :items="breadList"></b-breadcrumb>
    <router-view></router-view>
  </div>
</template>

<script>
const Cookie = process.browser ? require('js-cookie') : undefined
import AdminNavbar from '~/components/admin/AdminNavbar.vue';
export default {
  data() {
    return {
      breadList: [
        {
          text: '首页',
          href: '#'
        },
      ],
    }
  },
  mounted() {
    let token = Cookie.get('token')
    if (!token) {
      this.$router.push({ path: '/login' })
    }
  },
  components: {
    AdminNavbar
  }
}
</script>

<style lang="scss" scope>
.info-item {
  font-size: 20px;
  font-weight: 600;
  border: 1px solid #dee2e6;
}
.info-dark {
  background-color: rgba(0, 0, 0, 0.05);
}
</style>
