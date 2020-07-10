<template>
  <div>
    <div class="container-fluid">
      <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20 col-md-4">
        <div class="login_header">
          <h1>Login</h1>
        </div>

        <div class="space">
          <Input type="email" v-model="data.email" placeholder="Email" />
        </div>
        <div class="space">
          <Input type="password" v-model="data.password" placeholder="Password" />
        </div>
        <div class="login_footer" @click="login" :disabled="isLogging" :loading="isLogging">
          <Button type="primary">{{ isLogging ? 'Logging...' : 'Login'}}</Button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      data: {
        email: "",
        password: ""
      },
      isLogging: false
    };
  },
  methods: {
    async login() {
      if (this.data.email.trim() == "") return this.error("Email is required");
      if (this.data.password.trim() == "")
        return this.error("Password is required");
      if (this.data.password.length < 6)
        return this.error("Incorrect login details");
      this.isLogging = true;
      const res = await this.callApi("post", "/app/admin_login", this.data);
      if (res.status === 200) {
        this.success(res.data.msg);
        window.location = '/';
      } else {
        if (res.status === 401) {
          this.error(res.data.msg);
        } else if (res.status == 422) {
          console.log(res.data.msg);
          for (let i in res.data.errors) {
            this.error(res.data.errors[i][0]);
          }
        } else {
          this.swr();
        }
      }
      this.isLogging = false;
    }
  }
};
</script>


<style scoped>
/* scoped = only applies in this page */
._1adminOverveiw_table_recent {
  margin: 0 auto;
  margin-top: 120px;
}

.login_footer {
  text-align: center;
}
.login_header {
  text-align: center;
  margin-bottom: 25px;
}
</style>
