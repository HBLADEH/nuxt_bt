<template>
  <div>
    <b-breadcrumb :items="breadList"></b-breadcrumb>
    <b-card class="text-center" style="margin: 5px">
      <div class="container">
        <div class="row no-gutters mb-2">
          <div class="col-6 text-left">
            <b-button variant="primary" v-b-modal.modal-padd>添加</b-button>
          </div>
          <div class="col-6">
            <b-input-group>
              <b-form-input v-model="order" type="search" id="filterInput" placeholder="请输入单号"></b-form-input>
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
    <b-modal id="modal-padd" ref="paddModal" title="添加订单" @ok="doAdd" cancel-title="取消" ok-title="确认">
      <form ref="paddForm" @submit.stop.prevent="paddhandleSubmit">
        <b-form-group label="订单名称:" label-for="n-order" invalid-feedback="订单名必须要填写">
          <b-form-input id="n-n_order" v-model="n_order" required></b-form-input>
        </b-form-group>
        <b-form-group label="金额:" label-for="n-money" invalid-feedback="金额必须要填写">
          <b-form-input id="n-money" type="text" v-model="n_money" required></b-form-input>
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
      userId: '',
      perPage: 5, // 
      currentPage: 1, // 当前页数
      isBusy: false, // 是否忙碌
      pageCount: 0, // 数据总数
      order: '', // 检索单号
      n_order: '',
      n_money: '',
      tableFileds: [
        {
          key: 'id',
          label: 'ID',
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
        }, {
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
            order: this.order
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
      this.n_order = ''
      this.n_money = ''
    },

    // 检测验证值
    checkFormValidity() {
      const valid = this.$refs.addForm.checkValidity()
      this.$refs.addForm.classList.add('was-validated');
      return valid
    },

     // 添加页面的确认按钮
    doAdd(bvModalEvt) {
      // Prevent modal from closing
      bvModalEvt.preventDefault()
      this.paddhandleSubmit()
    },
    resetAddForm() {
      this.n_username = ''
      this.n_password = ''
      this.n_cpassword = ''
      this.n_roleid = 2
      this.n_is_lock = false
    },

    // 检测验证值
    checkFormValidity(target) {
      const valid = this.$refs[target].checkValidity()
      this.$refs[target].classList.add('was-validated');
      return valid
    },

    //提交事件
    paddhandleSubmit() {
      // Exit when the form isn't valid
      if (!this.checkFormValidity('paddForm')) {
        return
      }
      this.addPost()
    },

    // 提交添加请求
    addPost() {
      this.$axios.post('/admin/relation/doAdd', qs.stringify(
        {
          order: this.n_order,
          money: this.n_money,
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
            this.$bvModal.hide('modal-padd')
          }
        })
    },

    // // 显示编辑页面
    // showEdit(id) {
    //   this.$axios.get('/admin/admin/edit', {
    //     params: {
    //       id: id,
    //     }
    //   }).then(res => {
    //     this.u_id = res.data.id
    //     this.u_username = res.data.username
    //     this.u_roleid = res.data.roleid
    //     this.u_is_lock = res.data.is_lock >= 5 ? true : false
    //     this.$bvModal.show('modal-edit')
    //   })
    // },

    // // 执行编辑操作
    // doEdit() {
    //   this.$axios.post('/admin/admin/doEdit', qs.stringify(
    //     {
    //       id: this.u_id,
    //       username: this.u_username,
    //       roleid: this.u_roleid,
    //       is_lock: this.u_is_lock ? 5 : 1,
    //     })).then(res => {
    //       let resState = res.data.success
    //       let variant = 'danger'
    //       let title = '操作错误'

    //       if (resState) {
    //         variant = 'success'
    //         title = '操作成功'
    //         this.getTableData()
    //       }

    //       // 显示提示框
    //       this.$bvToast.toast(res.data.msg, {
    //         title: title,
    //         variant: variant,
    //         solid: true,
    //         autoHideDelay: 2000,
    //       })
    //     })
    // },


    // 删除操作
    doDelete(id) {
      this.$axios.post('/admin/relation/doDelete', qs.stringify(
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
