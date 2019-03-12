<template>
  <span>
    <el-tooltip class="item" effect="dark" content="Editar" placement="top">
      <el-button icon="el-icon-edit" @click="dialogVisible = true"></el-button>
    </el-tooltip>
    <el-dialog
      title="Editar Servicio"
      :visible.sync="dialogVisible"
      width="40%"
      :before-close="handleClose"
    >
      <el-row>
        <el-col :span="24">
          <el-form
            :label-position="labelPosition"
            :rules="rules"
            :model="service"
            label-width="150px"
            ref="serviceForm"
          >
            <el-form-item label="Nombre del Servicio" prop="name">
              <el-input v-model="service.name"></el-input>
            </el-form-item>
            <el-form-item label="Descripcion" prop="description">
              <el-input type="textarea" v-model="service.description"></el-input>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
      <span slot="footer" class="dialog-footer">
        <el-button @click="cancel()">Cancelar</el-button>
        <el-button type="primary" @click="saveService()">Guardar</el-button>
      </span>
    </el-dialog>
  </span>
</template>
<script>
export default {
  props: ["service"],
  data() {
    return {
      dialogVisible: false,
      labelPosition: "left",
      rules: {
        name: [
          {
            required: true,
            message: "Campo Nombre es obligatorio",
            trigger: "change"
          }
        ]
      }
    };
  },
  methods: {
    handleClose(done) {
      $this.cancel();
      done();
    },
    cancel() {
      this.dialogVisible = false;
      this.$refs.serviceForm.resetFields();
    },
    saveService() {
      var $this = this;
      $this.$refs.serviceForm.validate(valid => {
        if (valid) {
          axios
            .post("/api/services", $this.service)
            .then(function(response) {
              $this.$notify({
                title: "¡Exito!",
                message: "El Servicio fue editado correctamente",
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
