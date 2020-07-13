//添加插件文件 plugins/axios.js
let isClient = process.env.VUE_ENV === 'client' //区分端
export default ({ redirect, $axios, app }) => {
  $axios.onRequest(config => {
    return new Promise((resolve, reject) => {
      //match api
      let token = app.$cookiz.get('token')
      //add token
      if (token) config.headers.Authorization = token;
      //其他的请求前业务逻辑 比如：api map
      resolve(config);
    })
  });

  // $axios.onResponse(res => {
  //   return new Promise((resolve, reject) => {
  //     //返回数据逻辑处理 比如：error_code错误处理
  //     resolve(res.data);
  //   })
  // });

  $axios.onError(config => {
    console.log('Making request to ' + config.url)
  })
};
