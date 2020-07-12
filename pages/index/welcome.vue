<template>
  <div>
    <b-card class="text-center" style="margin: 5px">
      <div class="container">
        <div class="row">
          <div class="col-sm info-item info-dark">属性</div>
          <div class="col-sm info-item info-dark">值</div>
        </div>
        <div class="row">
          <div class="col-sm info-item">属性</div>
          <div class="col-sm info-item">值</div>
        </div>
      </div>
    </b-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
    }
  },
  mounted() {
    this.$axios.post('/admin/admin/getWelcomeInfo',
    ).then(res => {

    })

  },
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
