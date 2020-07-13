<template>
  <div>
    <b-card class="text-center " style="margin: 5px">
      <div class="container ">
        <div class="row">
          <div class="col-sm info-item info-dark">服务器地址</div>
          <div class="col-sm info-item info-dark">{{infoList.SERVER_ADDR}}</div>
        </div>
        <div class="row">
          <div class="col-sm info-item">服务器名称</div>
          <div class="col-sm info-item">{{infoList.SERVER_NAME}}</div>
        </div>
        <div class="row">
          <div class="col-sm info-item info-dark">服务器端口</div>
          <div class="col-sm info-item info-dark">{{infoList.SERVER_PORT}}</div>
        </div>
        <div class="row">
          <div class="col-sm info-item">服务器版本</div>
          <div class="col-sm info-item">{{infoList.SERVER_VERSION}}</div>
        </div>
        <div class="row">
          <div class="col-sm info-item info-dark">服务器系统</div>
          <div class="col-sm info-item info-dark">{{infoList.SERVER_SYSTEM}}</div>
        </div>
        <div class="row">
          <div class="col-sm info-item">PHP版本</div>
          <div class="col-sm info-item">{{infoList.PHP_VERSION}}</div>
        </div>
        <div class="row">
          <div class="col-sm info-item info-dark">ThinkPHP版本</div>
          <div class="col-sm info-item info-dark">{{infoList.TP_VERSION}}</div>
        </div>
        <div class="row">
          <div class="col-sm info-item">ThinkPHP版本</div>
          <div class="col-sm info-item">{{infoList.TP_LANGUAGE}}</div>
        </div>
        <div class="row">
          <div class="col-sm info-item info-dark">ThinkPHP模式</div>
          <div class="col-sm info-item info-dark">{{infoList.TP_RUNNINGWAY}}</div>
        </div>
      </div>
    </b-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      infoList: {}
    }
  }, async asyncData({ $axios }) {
    //发送ajax
    let { data } = await $axios.get('/admin/index/getWelcomeInfo')
    //封装
    return {
      infoList: data.data
    }
  },
  middleware: 'authenticated'
}
</script>

<style lang="scss" scope>
.info-item {
  font-size: 16px;
  font-weight: 600;
  border: 1px solid #dee2e6;
}
.info-dark {
  background-color: rgba(0, 0, 0, 0.05);
}
</style>
