<template>
  <el-col :span="5">
    <el-button
      type="primary"
      icon="el-icon-circle-plus"
      @click="dialogVisible = true"
      style="float:right;"
    >Agregar un Usuario</el-button>
    <el-dialog
      title="Agregar un Usuario"
      :visible.sync="dialogVisible"
      width="40%"
      :before-close="handleClose"
    >
      <el-row>
        <el-col :span="24">
          <el-form
            :label-position="labelPosition"
            :rules="rules"
            :model="user"
            label-width="150px"
            ref="userForm"
          >
            <el-form-item label="Nombre" prop="name">
              <el-input v-model="user.name"></el-input>
            </el-form-item>
            <el-form-item label="Correo Electronico" prop="email">
              <el-input v-model="user.email"></el-input>
            </el-form-item>
            <el-form-item label="Contraseña" prop="password">
              <el-input v-model="user.password"></el-input>
            </el-form-item>
            <el-form-item label="Rol" prop="role">
              <el-select v-model="user.role" placeholder="Selecciona rol">
                <el-option
                  v-for="role in roles"
                  :key="role.id"
                  :label="role.name"
                  :value="role.id"
                >{{role.name}}</el-option>
              </el-select>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
      <span slot="footer" class="dialog-footer">
        <el-button @click="cancel()">Cancelar</el-button>
        <el-button type="primary" @click="saveUser()" :loading="loading">Agregar</el-button>
      </span>
    </el-dialog>
  </el-col>
</template>
<script>
export default {
  props: ["roles"],
  data() {
    return {
      dialogVisible: false,
      loading: false,
      labelPosition: "left",
      user: {
        name: "",
        email: "",
        password: "",
        role: ""
      },
      rules: {
        name: [
          {
            required: true,
            message: "Campo Nombre es obligatorio",
            trigger: "change"
          }
        ],
        email: [
          {
            required: true,
            message: "Campo Correo Electronico es obligatorio",
            trigger: "change"
          },
          {
            type: "email",
            message: "Correo Electronico invalido",
            trigger: "change"
          }
        ],
        password: [
          {
            required: true,
            message: "Campo Contraseña es obligatorio",
            trigger: "change"
          }
        ],
        role: [
          {
            required: true,
            message: "Campo Rol es obligatorio",
            trigger: "change"
          }
        ]
      }
    };
  },
  methods: {
    handleClose(done) {
      var $this = this;
      if ($this.user.name || $this.user.email || $this.user.password) {
        $this
          .$confirm("¿Estas seguro de no guardar el Usuario?")
          .then(_ => {
            $this.cancel();
            done();
          })
          .catch(_ => {});
      } else {
        $this.cancel();
        done();
      }
    },
    cancel() {
      this.dialogVisible = false;
      this.loading = false;
      this.$refs.userForm.resetFields();
    },
    saveUser() {
      var $this = this;
      $this.$refs.userForm.validate(valid => {
        if (valid) {
          $this.loading = true;
          axios
            .post("/api/users", $this.user)
            .then(function(response) {
              $this.$notify({
                title: "¡Exito!",
                message: "El Usuario fue agregado correctamente",
                type: "success"
              });
              $this.$root.$emit("refreshTable");
              $this.cancel();
            })
            .catch(error => {
              if (error.response.data.errors) {
                var errors = error.response.data.errors;
                $this.$alert(errors[Object.keys(errors)[0]][0], "Error", {
                  confirmButtonText: "OK",
                  type: "error"
                });
              }
              $this.loading = false;
            });
        } else {
          return false;
        }
      });
    }
  }
};
</script>
