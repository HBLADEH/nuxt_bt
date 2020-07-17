<template>
  <div>
    <b-navbar toggleable="lg" type="dark" variant="primary">
      <nuxt-link class="navbar-brand" to="/welcome">PJ后台</nuxt-link>
      <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

      <b-collapse id="nav-collapse" is-nav>
        <b-navbar-nav>
          <li class="nav-item">
            <nuxt-link class="nav-link" to="/admin">管理员管理</nuxt-link>
          </li>
          <li class="nav-item">
            <nuxt-link class="nav-link" to="/product">订单管理</nuxt-link>
          </li>
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
            <b-dropdown-item href="#" v-b-modal.modal-changepassword>修改密码</b-dropdown-item>
            <b-dropdown-item href="#" @click="doLoginOut">登出</b-dropdown-item>
          </b-nav-item-dropdown>
        </b-navbar-nav>
      </b-collapse>
    </b-navbar>
    <b-modal
      id="modal-changepassword"
      title="修改密码"
      @ok="doChangePassword"
      cancel-title="取消"
      ok-title="确认"
    >
      <form ref="cpForm" @submit.stop.prevent="handleSubmit">
        <b-form-group label="用户旧密码:" label-for="oldpass" invalid-feedback="必须要填写">
          <b-form-input id="oldpass" type="password" v-model="oldpass" required></b-form-input>
        </b-form-group>
        <b-form-group label="用户新密码:" label-for="newpass" invalid-feedback="必须要填写">
          <b-form-input id="newpass" type="password" v-model="newpass" required></b-form-input>
        </b-form-group>
        <b-form-group label="确认新密码:" label-for="cnewpass" invalid-feedback="要和新密码一致">
          <b-form-input
            id="cnewpass"
            type="password"
            v-model="cnewpass"
            required
            :pattern="newpass"
          ></b-form-input>
        </b-form-group>
      </form>
    </b-modal>
  </div>
</template>

<script>
import qs from "qs";
export default {
  data() {
    return {
      username: '',
      oldpass: '',
      newpass: '',
      cnewpass: '',

    }
  },
  created() {
  },
  mounted() {
    this.username = this.$cookiz.get('username')

    // 此为弹出框隐藏后的判断
    this.$root.$on('bv::toast:hidden', event => {
      if (!this.$cookiz.get('token')) {
        this.$router.push({ path: '/login' })
      }
    })
  },
  methods: {
    doLoginOut() {
      this.$cookiz.remove('token');
      this.$cookiz.remove('username');
      this.$cookiz.remove('userid');
      // 显示提示框
      this.$bvToast.toast('登出成功', {
        title: '登出成功',
        variant: 'success',
        solid: true,
        autoHideDelay: 2000,
      })
    },
    //提交事件
    handleSubmit() {
      // Exit when the form isn't valid
      if (!this.checkFormValidity()) {
        return
      }
      this.cpPost()
    },
    doChangePassword(bvModalEvt) {
      // Prevent modal from closing
      bvModalEvt.preventDefault()
      this.handleSubmit()
    },
    // 检测验证值
    checkFormValidity() {
      const valid = this.$refs.cpForm.checkValidity()
      this.$refs.cpForm.classList.add('was-validated');
      return valid
    },
    cpPost() {
      this.$axios.post('/admin/admin/doChangePassWord', qs.stringify(
        {
          id: this.$cookiz.get('userid'),
          old_password: this.oldpass,
          new_password: this.newpass,
        })).then(res => {

          let resState = res.data.success
          let variant = 'danger'
          let title = '操作错误'
          console.log(resState);
          if (resState) {
            variant = 'success'
            title = '操作成功'
          }

          // 显示提示框
          this.$bvToast.toast(res.data.msg, {
            title: title,
            variant: variant,
            solid: true,
            autoHideDelay: 2000,
          })

          if (resState) {
            this.oldpass = ''
            this.newpass = ''
            this.cnewpass = ''
            this.$bvModal.hide('modal-changepassword')
          }
        })
    }
  }
}
</script>

<style lang="scss" scoped>
</style>