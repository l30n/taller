<template>
  <el-dialog
    :title="edit ? 'Editar recibo': 'Cambiar orden de estado'"
    :visible.sync="dialogVisible"
    width="450px"
  >
    <el-row class="confirm">
      <el-col
        :span="24"
        style="margin-bottom: 10px;"
        v-if="!edit"
      >¿Quieres cambiar el estado la Orden de Servicio?</el-col>
      <el-col :span="24">
        <el-form ref="form" label-width="120px">
          <el-form-item label="Empleado:">
            <el-select v-model="user" placeholder="Empleado">
              <el-option v-for="user in users" :key="user.id" :label="user.name" :value="user.id"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="Marca:">
            <el-input v-model="maker"></el-input>
          </el-form-item>
          <el-form-item label="Modelo:">
            <el-input v-model="brand"></el-input>
          </el-form-item>
          <el-form-item label="Año:">
            <el-input v-model="year"></el-input>
          </el-form-item>
          <el-form-item label="Color:">
            <el-input v-model="color"></el-input>
          </el-form-item>
          <el-form-item label="Concepto:">
            <el-input v-model="concept"></el-input>
          </el-form-item>
          <el-form-item label="Detalle:">
            <el-input type="textarea" :rows="3" v-model="details"></el-input>
          </el-form-item>
          <el-form-item label="Garantia:">
            <el-input v-model="guaranty"></el-input>
          </el-form-item>
          <el-form-item label="Total:">
            <el-input v-model="total"></el-input>
          </el-form-item>
          <el-form-item label="Metodo de Pago:">
            <el-radio-group v-model="method">
              <el-radio :label="1" name="type">Efectivo</el-radio>
              <el-radio :label="2" name="type">Electronico</el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item label="IVA:">
            <el-checkbox v-model="tax"></el-checkbox>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>
    <span slot="footer" class="dialog-footer">
      <el-button @click="dialogVisible = false">No</el-button>
      <el-button type="primary" @click="save" :loading="loading">Si</el-button>
    </span>
  </el-dialog>
</template>

<script>
export default {
  data() {
    return {
      dialogVisible: false,
      edit: false,
      concept: "",
      maker: "",
      brand: "",
      year: "",
      color: "",
      details: "",
      guaranty: "",
      total: "",
      method: 1,
      user: "",
      tax: false,
      users: [],
      sale: {},
      loading: false
    };
  },
  mounted: function() {
    var $this = this;

    axios.get("/api/users?all=1&role=Empleado").then(function(response) {
      $this.users = response.data;
    });

    this.$root.$on("confirmSale", this.openDialog);
  },
  methods: {
    openDialog: function(sale, edit = false) {
      this.dialogVisible = true;
      this.edit = edit;
      this.sale = sale;
      this.concept = sale.concept;
      this.maker = sale.maker ? sale.maker : sale.car[0].maker;
      this.brand = sale.brand ? sale.brand : sale.car[0].brand;
      this.year = sale.year ? sale.year : sale.sale_services[0].year;
      this.color = sale.color;
      this.details = sale.details;
      this.guaranty = sale.guaranty;
      this.tax = sale.tax;
      this.method = sale.method ? sale.method : 1;
      this.user = sale.user_id;
      this.total = sale.total;
    },
    save: function() {
      var $this = this;
      $this.loading = true;
      axios
        .post("/api/sales/status", {
          id: $this.sale.id,
          status: $this.sale.status,
          method: $this.method,
          concept: $this.concept,
          maker: $this.maker,
          brand: $this.brand,
          year: $this.year,
          color: $this.color,
          total: $this.total,
          user: $this.user,
          details: $this.details,
          guaranty: $this.guaranty,
          tax: $this.tax
        })
        .then(function(response) {
          $this.dialogVisible = false;
          $this.loading = false;
          $this.$root.$emit("refreshTable");
          $this.$root.$emit("refreshReceipt", response.data.sale);
          $this.$message({
            type: "success",
            message: "Cambio de estatus exitoso"
          });
        });
    }
  }
};
</script>
<style lang="scss">
.confirm {
  .el-dialog__header {
    padding: 15px;
  }
  .el-dialog__body {
    padding: 10px 15px;
  }
  .el-form-item {
    margin-bottom: 10px;
  }
}
</style>
