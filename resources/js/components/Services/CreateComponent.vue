<template>
  <el-col :span="5">
    <el-button
      type="primary"
      icon="el-icon-circle-plus"
      @click="dialogVisible = true"
      style="float:right;"
    >Agregar un Servicio</el-button>
    <el-dialog
      title="Agregar un Servicio"
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
        <el-button type="primary" @click="saveService()" :loading="loading">Agregar</el-button>
      </span>
    </el-dialog>
  </el-col>
</template>
<script>
export default {
  data() {
    return {
      dialogVisible: false,
      loading: false,
      labelPosition: "left",
      service: {
        name: "",
        description: ""
      },
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
      var $this = this;
      if ($this.service.name) {
        $this
          .$confirm("¿Estas seguro de no guardar el Servicio?")
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
      this.$refs.serviceForm.resetFields();
    },
    saveService() {
      var $this = this;
      $this.$refs.serviceForm.validate(valid => {
        if (valid) {
          $this.loading = true;
          axios
            .post("/api/services", $this.service)
            .then(function(response) {
              $this.$notify({
                title: "¡Exito!",
                message: "El Servicio fue agregado correctamente",
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
