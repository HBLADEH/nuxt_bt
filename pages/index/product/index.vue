<template>
  <div>
    <b-breadcrumb :items="breadList"></b-breadcrumb>
    <b-card class="text-center" style="margin: 5px">
      <div class="container">
        <div class="row no-gutters mb-2">
          <!-- <div class="col-6 text-left">
            <b-button variant="primary" v-b-modal.modal-add>添加</b-button>
          </div>-->
          <div class="col-6">
            <b-input-group>
              <b-form-input v-model="username" type="search" id="filterInput" placeholder="请输入用户名"></b-form-input>
              <b-input-group-append>
                <b-button @click="getTableData" variant="success">搜索</b-button>
              </b-input-group-append>
            </b-input-group>
          </div>
        </div>
        <b-table
          id="data-table"
          table-variant="primary"
          responsive
          bordered
          hover
          :busy="isBusy"
          :items="tableItems"
          :fields="tableFileds"
        >
          <template v-slot:table-busy>
            <div class="text-center text-danger my-2">
              <b-spinner class="align-middle"></b-spinner>
              <strong>Loading...</strong>
            </div>
          </template>
          <template v-slot:cell(actions)="row">
            <b-button-group>
              <b-button size="sm" variant="info" @click="showEdit(row.item.id)">修改</b-button>
              <b-button size="sm" variant="danger" @click="doDelete(row.item.id)">删除</b-button>
            </b-button-group>
          </template>
        </b-table>
        <b-pagination
          v-model="currentPage"
          :total-rows="rows"
          :per-page="perPage"
          aria-controls="data-table"
          align="center"
          @input="changePage"
        ></b-pagination>
      </div>
    </b-card>

    <b-modal id="modal-add" ref="addModal" title="添加数据" @ok="doAdd" cancel-title="取消" ok-title="确认">
      <form ref="addForm" @submit.stop.prevent="handleSubmit">
        <b-form-group label="用户名称:" label-for="n-username" invalid-feedback="用户名必须要填写">
          <b-form-input id="n-username" v-model="n_username" required></b-form-input>
        </b-form-group>
        <b-form-group label="用户密码:" label-for="n-password" invalid-feedback="用户密码必须要填写">
          <b-form-input id="n-password" type="password" v-model="n_password" required></b-form-input>
        </b-form-group>
        <b-form-group label="确认密码:" label-for="n-cpassword" invalid-feedback="确认密码必须要一致">
          <b-form-input
            id="n-cpassword"
            type="password"
            v-model="n_cpassword"
            required
            :pattern="n_password"
          ></b-form-input>
        </b-form-group>
        <b-form-group label="用户角色:" label-for="n-cpassword" invalid-feedback="请选择一个角色">
          <b-form-select id="n-role" v-model="n_roleid" :options="roleList" required></b-form-select>
        </b-form-group>
        <div class="custom-control custom-switch text-center">
          <input
            type="checkbox"
            class="custom-control-input"
            id="customSwitch1"
            v-model="n_is_lock"
          />
          <label class="custom-control-label" for="customSwitch1">是否禁用</label>
        </div>
      </form>
    </b-modal>

    <b-modal id="modal-edit" title="编辑数据" @ok="doEdit" cancel-title="取消" ok-title="确认">
      <form ref="editForm" @submit.stop.prevent="handleSubmit">
        <b-form-group label="用户名称:" label-for="u-username" invalid-feedback="用户名必须要填写">
          <b-form-input id="u-username" v-model="u_username" required></b-form-input>
        </b-form-group>
        <b-form-group label="用户角色:" label-for="u-role" invalid-feedback="请选择一个角色">
          <b-form-select id="u-role" v-model="u_roleid" :options="roleList" required></b-form-select>
        </b-form-group>
        <div class="custom-control custom-switch text-center">
          <input
            type="checkbox"
            class="custom-control-input"
            id="customSwitch2"
            v-model="u_is_lock"
          />
          <label class="custom-control-label" for="customSwitch2">是否禁用</label>
        </div>
      </form>
    </b-modal>
  </div>
</template>

<script>
import qs from "qs";
export default {
  data() {
    return {
      userId: '',
      perPage: 5, // 
      currentPage: 1, // 当前页数
      isBusy: false, // 是否忙碌
      pageCount: 0, // 数据总数
      username: '', // 检索用户名
      // 添加页面的数据参数
      n_username: '',
      n_password: '',
      n_cpassword: '',
      n_roleid: 2,
      n_is_lock: false,
      // 编辑页面的数据参数
      u_id: 0,
      u_username: '',
      u_roleid: 2,
      u_is_lock: false,
      roleList: [{ text: '管理员', value: 1 }, { text: '订单处理员', value: 2 }],
      tableFileds: [
        {
          key: 'id',
          label: '用户ID',
        },
        {
          key: 'order',
          label: '单号',
        },
        {
          key: 'money',
          label: '金额',
          sortable: false
        },
        {
          key: 'purchase_time',
          label: '提交时间',
        }, { key: 'actions', label: '操作' }
      ],
      tableItems: [],
      breadList: [
        {
          text: '首页',
          href: '/welcome'
        },{
          text: '订单管理',
          href: '/product'
        }
      ],
    }
  },
  created() {
  },
  mounted() {
    this.getTableData()
  },
  computed: {
    rows() {
      return this.pageCount
    }
  }, methods: {
    getTableData() {
      this.toggleBusy() // 先让表处于繁忙状态
      this.$axios.get('/admin/relation/FindAll',
        {
          params: {
            page: this.currentPage,
            limit: this.perPage,
            username: this.username
          }
        }).then(res => {
          this.tableItems = res.data.data
          this.pageCount = res.data.count
          this.toggleBusy() // 恢复状态
        })
    },
    // 切换页面
    changePage(page) {
      this.getTableData()
    },

    // 切换忙碌状态
    toggleBusy() {
      this.isBusy = !this.isBusy
    },

    // 添加页面的确认按钮
    doAdd(bvModalEvt) {
      // Prevent modal from closing
      bvModalEvt.preventDefault()
      this.handleSubmit()
    },
    resetAddForm() {
      this.n_username = ''
      this.n_password = ''
      this.n_cpassword = ''
      this.n_roleid = 2
      this.n_is_lock = false
    },

    // 检测验证值
    checkFormValidity() {
      const valid = this.$refs.addForm.checkValidity()
      this.$refs.addForm.classList.add('was-validated');
      return valid
    },

    //提交事件
    handleSubmit() {
      // Exit when the form isn't valid
      if (!this.checkFormValidity()) {
        return
      }
      this.addPost()
    },

    // 提交添加请求
    addPost() {
      this.$axios.post('/admin/admin/doAdd', qs.stringify(
        {
          username: this.n_username,
          password: this.n_password,
          roleid: this.n_roleid,
          is_lock: this.n_is_lock ? 5 : 1,
        })).then(res => {


          let resState = res.data.success
          let variant = 'danger'
          let title = '操作错误'

          if (resState) {
            variant = 'success'
            title = '操作成功'
            this.getTableData()
            this.resetAddForm()
          }

          this.getTableData()
          // 显示提示框
          this.$bvToast.toast(res.data.msg, {
            title: title,
            variant: variant,
            solid: true,
            autoHideDelay: 2000,
          })

          if (resState) {
            this.$bvModal.hide('modal-add')
          }
        })
    },

    // 显示编辑页面
    showEdit(id) {
      this.$axios.get('/admin/admin/edit', {
        params: {
          id: id,
        }
      }).then(res => {
        this.u_id = res.data.id
        this.u_username = res.data.username
        this.u_roleid = res.data.roleid
        this.u_is_lock = res.data.is_lock >= 5 ? true : false
        this.$bvModal.show('modal-edit')
      })
    },

    // 执行编辑操作
    doEdit() {
      this.$axios.post('/admin/admin/doEdit', qs.stringify(
        {
          id: this.u_id,
          username: this.u_username,
          roleid: this.u_roleid,
          is_lock: this.u_is_lock ? 5 : 1,
        })).then(res => {
          let resState = res.data.success
          let variant = 'danger'
          let title = '操作错误'

          if (resState) {
            variant = 'success'
            title = '操作成功'
            this.getTableData()
          }

          // 显示提示框
          this.$bvToast.toast(res.data.msg, {
            title: title,
            variant: variant,
            solid: true,
            autoHideDelay: 2000,
          })
        })
    },


    // 删除操作
    doDelete(id) {
      this.$axios.post('/admin/admin/doDelete', qs.stringify(
        {
          id: id,
        })).then(res => {
          let resState = res.data.success
          let variant = 'danger'
          let title = '操作错误'

          if (resState) {
            variant = 'success'
            title = '操作成功'
            this.getTableData()
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
  middleware: 'authenticated'
}
</script>

<style lang="scss" scope>
</style>
