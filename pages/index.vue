<template>
  <div>
    <AdminNavbar></AdminNavbar>
    <router-view></router-view>
  </div>
</template>

<script>
import AdminNavbar from '~/components/admin/AdminNavbar.vue';
export default {
  data() {
    return {
 
    }
  },
  created() {
    // this.$router.push({ path: '/welcome' })
  }
  ,
  mounted() {
  },
  components: {
    AdminNavbar
  },
  middleware: 'authenticated'
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
