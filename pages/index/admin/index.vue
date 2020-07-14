<template>
  <div>
    <b-card class="text-center" style="margin: 5px">
      <div class="container">
        <div class="row no-gutters mb-2">
          <div class="col-6 text-left">
            <b-button variant="primary" v-b-modal.modal-add>添加</b-button>
          </div>
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
              <b-button size="sm" variant="info" v-b-modal.modal-edit>修改</b-button>
              <b-button size="sm" variant="danger">删除</b-button>
            </b-button-group>
            <!-- <b-button size="sm" @click="info(row.item, row.index, $event.target)" class="mr-1">修改</b-button>
            <b-button size="sm" @click="row.toggleDetails">删除</b-button>-->
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
    <b-modal id="modal-add" title="添加数据">
      <p class="my-4">添加数据页面</p>
    </b-modal>
    <b-modal id="modal-edit" title="编辑数据">
      <p class="my-4">编辑数据页面</p>
    </b-modal>
  </div>
</template>

<script>
export default {
  data() {
    return {
      perPage: 3, // 
      currentPage: 1, // 当前页数
      isBusy: false, // 是否忙碌\
      pageCount: 0, // 数据总数
      username: '', // 检索用户名
      tableFileds: [
        {
          key: 'id',
          label: '用户ID',
        },
        {
          key: 'username',
          label: '用户名',
        },
        {
          key: 'just',
          label: '用户权限',
          sortable: false
        },
        {
          key: 'is_lock',
          label: '是否被禁用',
        }, { key: 'actions', label: '操作' }
      ],
      tableItems: []
    }
  }, async asyncData({ $axios }) {
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
      this.toggleBusy()

      this.$axios.get('/admin/admin/FindAll',
        {
          params: {
            page: this.currentPage,
            limit: this.perPage,
            username: this.username
          }
        }).then(res => {
          // console.log(res);
          this.tableItems = res.data.data
          this.pageCount = res.data.count
          this.toggleBusy()
        })
    },
    // 切换页面
    changePage(page) {
      this.getTableData()
    },
    // 切换忙碌状态
    toggleBusy() {
      this.isBusy = !this.isBusy
    }
  },
  middleware: 'authenticated'
}
</script>

<style lang="scss" scope>
</style>
