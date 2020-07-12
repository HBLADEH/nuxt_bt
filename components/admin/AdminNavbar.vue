<template>
  <div>
    <b-navbar toggleable="lg" type="dark" variant="primary">
      <nuxt-link class="navbar-brand" to="/welcome">PJ后台</nuxt-link>
      <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

      <b-collapse id="nav-collapse" is-nav>
        <b-navbar-nav>
          <b-nav-item href="#">管理员管理</b-nav-item>
          <b-nav-item href="#">管理</b-nav-item>
        </b-navbar-nav>

        <b-navbar-nav class="ml-auto">
          <b-nav-item-dropdown text="语言" right>
            <b-dropdown-item href="#">EN</b-dropdown-item>
            <b-dropdown-item href="#">中文</b-dropdown-item>
          </b-nav-item-dropdown>

          <b-nav-item-dropdown right>
            <!-- Using 'button-content' slot -->
            <template v-slot:button-content>
              <em>{{username}}</em>
            </template>
            <b-dropdown-item href="#" @click="doLoginOut">登出</b-dropdown-item>
          </b-nav-item-dropdown>
        </b-navbar-nav>
      </b-collapse>
    </b-navbar>
  </div>
</template>

<script>
const Cookie = process.browser ? require('js-cookie') : undefined
export default {
  data() {
    return {
      username: ''
    }
  },
  created() {
    this.$router.push({ path: '/welcome' })
  },
  mounted() {
    this.username = Cookie.get('username')

    // 此为弹出框隐藏后的判断
    this.$root.$on('bv::toast:hidden', event => {
      if (!Cookie.get('token')) {
        this.$router.push({ path: '/login' })
      }
    })
  },
  methods: {
    doLoginOut() {
      Cookie.remove('token');
      Cookie.remove('username');
      // 显示提示框
      this.$bvToast.toast('登出成功', {
        title: '登出成功',
        variant: 'success',
        solid: true,
        autoHideDelay: 2000,
      })
    }
  }
}
</script>

<style lang="scss" scoped>
</style>