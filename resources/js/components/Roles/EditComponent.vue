<template>
  <el-col :span="5">
    <el-button icon="el-icon-edit" @click="dialogVisible = true">Editar</el-button>
    <el-dialog
      title="Agregar un Role"
      :visible.sync="dialogVisible"
      width="40%"
      :before-close="handleClose"
    >
      <el-row>
        <el-col :span="24">
          <el-form
            :label-position="labelPosition"
            :rules="rules"
            :model="localRole"
            label-width="150px"
            ref="roleForm"
          >
            <el-form-item label="Nombre" prop="name">
              <el-input v-model="localRole.name"></el-input>
            </el-form-item>
            <el-form-item label="Permisos" prop="permissions">
              <el-checkbox-group v-model="localRole.permissions" @change="changePermissions">
                <el-checkbox
                  v-for="(permission) in permissions"
                  v-bind:key="permission.id"
                  :value="permission.id"
                  :label="permission.id"
                >{{ permission.name }}</el-checkbox>
              </el-checkbox-group>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
      <span slot="footer" class="dialog-footer">
        <el-button @click="cancel()">Cancelar</el-button>
        <el-button type="primary" @click="saveRole()">Agregar</el-button>
      </span>
    </el-dialog>
  </el-col>
</template>
<script>
export default {
  props: ["role", "permissions"],
  beforeUpdate: function() {
    this.localRole = JSON.parse(JSON.stringify(this.role));
    for (var permission in this.localRole.permissions) {
      this.localRole.permissions[permission] = this.localRole.permissions[
        permission
      ].id;
    }
  },
  data() {
    return {
      dialogVisible: false,
      labelPosition: "left",
      localRole: {
        name: "",
        permissions: []
      },
      rules: {
        name: [
          {
            required: true,
            message: "Campo Nombre es obligatorio",
            trigger: "change"
          }
        ],
        permissions: [
          {
            type: "array",
            required: true,
            message: "Campo Permisos es obligatorio",
            trigger: "change"
          }
        ]
      }
    };
  },
  methods: {
    handleClose(done) {
      var $this = this;
      if ($this.localRole.name) {
        $this
          .$confirm("¿Estas seguro de no guardar el Rol?")
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
    changePermissions(value) {
      console.log(value);
      this.$forceUpdate();
    },
    cancel() {
      this.dialogVisible = false;
      this.$refs.roleForm.resetFields();
    },
    saveRole() {
      var $this = this;
      $this.$refs.roleForm.validate(valid => {
        if (valid) {
          axios
            .post("/api/roles", $this.localRole)
            .then(function(response) {
              $this.$notify({
                title: "¡Exito!",
                message: "El Rol fue agregado correctamente",
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
            });
        } else {
          return false;
        }
      });
    }
  }
};
</script>
<style lang="scss">
.el-checkbox {
  margin-right: 10px;
}
.el-checkbox + .el-checkbox {
  margin-left: 0;
}
</style>

