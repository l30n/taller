<template>
  <span>
    <el-tooltip class="item" effect="dark" content="Eliminar" placement="top">
      <el-button icon="el-icon-delete" type="danger" @click="dialogVisible = true"></el-button>
    </el-tooltip>
    <el-dialog title="Advertencia" :visible.sync="dialogVisible" width="30%" center>
      <span>
        ¿Desea eliminar el articulo
        <b>{{ item.name }}</b>?
      </span>
      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false">Cancelar</el-button>
        <el-button type="danger" @click="open" :loading="loading">Confirmar</el-button>
      </span>
    </el-dialog>
  </span>
</template>
<script>
export default {
  props: ["item"],
  data() {
    return {
      dialogVisible: false,
      loading: false
    };
  },
  methods: {
    open() {
      var $this = this;
      $this
        .$confirm(
          "Esto eliminara permanentemente el registro. ¿Desea continuar?",
          "Advertencia",
          {
            confirmButtonText: "OK",
            cancelButtonText: "Cancel",
            type: "warning"
          }
        )
        .then(() => {
          $this.loading = true;
          axios
            .delete("/api/items/" + $this.item.id)
            .then(function(response) {
              $this.$message({
                type: "success",
                message: "Articulo elminado"
              });
              $this.$root.$emit("refreshTable");
              $this.dialogVisible = false;
              $this.loading = false;
            })
            .catch(error => {
              $this.loading = false;
              if (error.response.data.errors) {
                var errors = error.response.data.errors;
                $this.$alert(errors[Object.keys(errors)[0]][0], "Error", {
                  confirmButtonText: "OK",
                  type: "error"
                });
              }
            });
        })
        .catch(() => {});
    }
  }
};
</script>
