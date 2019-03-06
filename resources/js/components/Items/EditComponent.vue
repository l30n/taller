<template>
  <el-col :span="5">
    <el-button icon="el-icon-edit" @click="dialogVisible = true">Editar</el-button>
    <el-dialog
      title="Editar un Articulo"
      :visible.sync="dialogVisible"
      width="40%"
      :before-close="handleClose"
    >
      <el-row>
        <el-col :span="24">
          <el-form
            :label-position="labelPosition"
            :rules="rules"
            :model="item"
            label-width="150px"
            ref="itemForm"
          >
            <el-form-item label="Nombre del Articulo" prop="name">
              <el-input v-model="item.name"></el-input>
            </el-form-item>
            <el-form-item label="Descripcion" prop="description">
              <el-input type="textarea" v-model="item.description"></el-input>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
      <span slot="footer" class="dialog-footer">
        <el-button @click="cancel()">Cancelar</el-button>
        <el-button type="primary" @click="saveItem()">Guardar</el-button>
      </span>
    </el-dialog>
  </el-col>
</template>
<script>
export default {
  props: ["item"],
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
      var $this = this;
      if ($this.item.name) {
        $this
          .$confirm("¿Estas seguro de no guardar el Articulo?")
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
      this.$refs.itemForm.resetFields();
    },
    saveItem() {
      var $this = this;
      $this.$refs.itemForm.validate(valid => {
        if (valid) {
          axios
            .post("/api/items", $this.item)
            .then(function(response) {
              $this.$notify({
                title: "¡Exito!",
                message: "Articulo fue editado correctamente",
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
