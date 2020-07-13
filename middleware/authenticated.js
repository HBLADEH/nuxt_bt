// 该中间件主要作用是进行权限判断
export default function({ app, redirect }) {
  let token = app.$cookiz.get("token");
  if (!token) {
    return redirect("/login");
  }
}
