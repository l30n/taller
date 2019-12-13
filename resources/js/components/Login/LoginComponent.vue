<template>
  <div
    class="g-center login-page"
    @keyup.enter="login"
  >
    <el-form
      class="login-form"
      method="POST"
      action="/login"
    >
      <input
        :value="token.content"
        name="_token"
        type="hidden"
      />
      <img
        src="/img/logo.png"
        width="50%"
        style="margin: 0 auto;display: block;"
      />
      <h1 class="main-title">Taller Gallegos</h1>
      <br>
      <el-form-item>
        <el-input
          :autofocus="true"
          placeholder="Correo Electronico"
          name="email"
          v-model="email"
        >
        </el-input>
      </el-form-item>
      <el-form-item>
        <el-input
          placeholder="Contraseña"
          type="password"
          name="password"
          v-model="password"
        >
        </el-input>
      </el-form-item>
      <el-form-item>
        <el-button
          style="width:100%"
          @click.native="login"
          type="primary"
          :loading="isBtnLoading"
          native-type="submit"
        >Iniciar Sesion</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>
<script>
export default {
  props: ["error"],
  data() {
    return {
      email: "",
      password: "",
      isBtnLoading: false
    };
  },
  mounted() {
    var $this = this;
    if ($this.error.length > 0) {
      this.$notify.error({
        title: "Error",
        message: $this.error[0]
      });
    }
  }
};
</script>

<style lang="scss">
#app {
  display: table;
  width: 100%;
}
.g-center {
  display: table-cell;
  vertical-align: middle;
}
.main-title {
  text-align: center;
}
.des {
  text-align: center;
  color: #999;
  margin-bottom: 2em;
}
.login-form {
  width: 300px;
  margin: 0 auto;
}
.login-page {
  background: #fff;
}
</style>
