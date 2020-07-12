<template>
  <div class="page login-page">
    <div class="container d-flex align-items-center">
      <div class="form-holder has-shadow">
        <div class="row">
          <!-- 表单页面 -->
          <div class="col bg-white">
            <div class="form d-flex align-items-center">
              <div class="content">
                <div class="title d-block d-md-none text-center">
                  <div class="logo">
                    <h1>欢迎登录</h1>
                  </div>
                  <p>PJ管理系统</p>
                </div>
                <!-- <b-form method="post" class="form-validate" id="loginForm" novalidate="novalidate"> -->
                <b-form @submit="doLogin">
                  <div class="form-group">
                    <input
                      id="username"
                      type="text"
                      name="username"
                      required
                      data-msg="请输入用户名"
                      v-model="form.username"
                      placeholder="用户名"
                      class="input-material is-valid"
                      aria-invalid="false"
                    />
                    <div id="login-username-error" class="is-invalid invalid-feedback">请输入用户名</div>
                  </div>
                  <div class="form-group">
                    <input
                      id="password"
                      type="password"
                      name="password"
                      required
                      data-msg="请输入密码"
                      v-model="form.password"
                      placeholder="密码"
                      class="input-material"
                    />
                    <div id="login-password-error" class="is-invalid invalid-feedback">请输入密码</div>
                  </div>
                  <button id="login" type="submit" class="btn btn-primary" onclick>登录</button>
                  <div style="margin-top: -40px;">
                    <div class="custom-control custom-checkbox" style="float: right;">
                      <input type="checkbox" class="custom-control-input" id="check2" />
                      <label class="custom-control-label" for="check2">自动登录</label>
                    </div>
                    <div class="custom-control custom-checkbox" style="float: right;">
                      <input type="checkbox" class="custom-control-input" id="check1" />
                      <label class="custom-control-label" for="check1">记住密码&nbsp;&nbsp;</label>
                    </div>
                  </div>
                </b-form>
                <br />
                <small>没有账号?</small>
                <a href="#" class="singup">&nbsp;注册</a>
              </div>
            </div>
          </div>
          <!-- 信息页面 -->
          <div class="col-lg-6 d-none d-md-block">
            <div class="info d-flex align-items-center">
              <div class="content">
                <div class="logo">
                  <h1>欢迎登录</h1>
                </div>
                <p>PJ管理系统</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <b-toast
        id="example-toast"
        title="BootstrapVue"
        static
        no-auto-hide
      >Hello, world! This is a toast message.</b-toast>
    </div>
  </div>
</template>

<script>
import qs from "qs";
const Cookie = process.browser ? require('js-cookie') : undefined

export default {
  data() {
    return {
      isLogin: false,
      form: {
        username: '',
        password: '',
      },
    }
  },
  mounted() {

    // 此为弹出框隐藏后的判断
    this.$root.$on('bv::toast:hidden', event => {
      if (this.isLogin && Cookie.get('token') != '') {
        this.$router.push({ path: '/' })
      }
    })
  },
  created() {
  },
  methods: {
    doLogin(evt) {
      evt.preventDefault()

      // alert(JSON.stringify(this.form))
      this.$axios.post('/admin/login/checkLogin', qs.stringify(this.form)
      ).then(res => {
        let resState = res.data.success
        let variant = 'danger'
        let title = '登录错误'
        // console.log(res.data.data.token);
        if (resState) {
          variant = 'success'
          title = '登录成功'
          this.isLogin = true

          // 存储 token
          Cookie.set('token', res.data.data.token)
          Cookie.set('username', res.data.data.username)
        }
        // 显示提示框
        this.$bvToast.toast(res.data.msg, {
          title: title,
          variant: variant,
          solid: true,
          autoHideDelay: 2000,
        })
      })
    }
  },
}
</script>

<style lang="scss">
@import "~/assets/login/css/login.scss";
</style>
