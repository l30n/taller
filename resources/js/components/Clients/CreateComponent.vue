<template>
  <el-col :span="5">
    <el-button
      type="primary"
      icon="el-icon-circle-plus"
      @click="dialogVisible = true"
      style="float:right;"
    >Agregar a un Cliente</el-button>
    <el-dialog
      title="Agregar a un Cliente"
      :visible.sync="dialogVisible"
      width="40%"
      :before-close="handleClose"
    >
      <el-row>
        <el-col :span="24">
          <el-form
            :label-position="labelPosition"
            :rules="rules"
            :model="client"
            label-width="150px"
            ref="clientForm"
          >
            <el-form-item label="Nombre" prop="name">
              <el-input v-model="client.name"></el-input>
            </el-form-item>
            <el-form-item label="Telefono" prop="phonenumber">
              <el-input v-model="client.phonenumber"></el-input>
            </el-form-item>
            <el-form-item label="Direccion" prop="address">
              <el-input type="textarea" v-model="client.address"></el-input>
            </el-form-item>
            <el-form-item label="Referencia" prop="reference">
              <el-input v-model="client.reference"></el-input>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
      <span slot="footer" class="dialog-footer">
        <el-button @click="cancel()">Cancelar</el-button>
        <el-button type="primary" @click="saveClient()">Agregar</el-button>
      </span>
    </el-dialog>
  </el-col>
</template>
<script>
export default {
  data() {
    return {
      dialogVisible: false,
      labelPosition: "left",
      client: {
        name: "",
        phonenumber: "",
        address: "",
        reference: ""
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
      if ($this.client.name) {
        $this
          .$confirm("¿Estas seguro de no guardar el Cliente?")
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
      this.$refs.clientForm.resetFields();
    },
    saveClient() {
      var $this = this;
      $this.$refs.clientForm.validate(valid => {
        if (valid) {
          axios
            .post("/api/clients", $this.client)
            .then(function(response) {
              $this.$notify({
                title: "¡Exito!",
                message: "Cliente fue agregado correctamente",
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
