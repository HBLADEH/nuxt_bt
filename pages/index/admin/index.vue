<template>
  <div>
    <b-card class="text-center" style="margin: 5px">
      <div class="container">
        <b-table
          id="data-table"
          table-variant="primary"
          responsive
          bordered
          hover
          :items="tableItems"
          :fields="tableFileds"
        >
          <template v-slot:cell(actions)="row">
            <b-button-group>
              <b-button size="sm" variant="info">修改</b-button>
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
        ></b-pagination>
      </div>
    </b-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      perPage: 3,
      currentPage: 1,
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
    //发送ajax
    let { data } = await $axios.get('/admin/admin/FindAll')
    //封装
    return {
      tableItems: data.data
    }
  },
  mounted() {
    // this.$axios.get('/admin/admin/FindAll'
    // ).then(res => {

    // })
  },
  middleware: 'authenticated'
}
</script>

<style lang="scss" scope>
</style>
