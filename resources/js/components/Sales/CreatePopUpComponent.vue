<template>
  <el-col :span="5" class="popup">
    <el-button
      type="primary"
      icon="el-icon-circle-plus"
      @click="dialogVisible = true"
    >Agregar Servicio</el-button>
    <el-dialog
      title="Agregar Servicio"
      :visible.sync="dialogVisible"
      width="30%"
      :before-close="handleClose"
    >
      <el-row>
        <el-col :span="24">
          <el-form
            :label-position="labelPosition"
            :rules="rules"
            :model="sale"
            label-width="150px"
            ref="saleForm"
          >
            <!--el-form-item label="Vehiculo" prop="brand">
              <el-select
                v-model="sale.brand"
                filterable
                placeholder="Selecciona un Vehiculo"
                @change="changeCar"
              >
                <el-option
                  v-for="brand in brands"
                  :key="brand.brand"
                  :label="brand.brand"
                  :value="brand.brand"
                ></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Año" prop="year">
              <el-select
                v-model="sale.year"
                filterable
                placeholder="Selecciona un Año"
                @change="changeCar"
              >
                <el-option v-for="year in years" :key="year" :label="year" :value="year"></el-option>
              </el-select>
            </el-form-item-->
            <el-form-item label="Servicio" prop="service">
              <el-select v-model="sale.service" filterable placeholder="Selecciona un Servicio">
                <el-option
                  v-for="service in services"
                  :key="service.id"
                  :label="service.name"
                  :value="service"
                ></el-option>
              </el-select>
            </el-form-item>
            <!--el-form-item label="Empleado" prop="user">
              <el-select v-model="sale.user" filterable placeholder="Selecciona un Empleado">
                <el-option v-for="user in users" :key="user.id" :label="user.name" :value="user.id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Cliente" prop="client">
              <el-select v-model="sale.client" filterable placeholder="Selecciona un Cliente">
                <el-option
                  v-for="client in clients"
                  :key="client.id"
                  :label="client.name"
                  :value="client.id"
                ></el-option>
              </el-select>
            </el-form-item-->
            <el-form-item
              label="Total"
              prop="total"
              :rules="{
                required: true, message: 'Campo Total es obligatorio', trigger: 'blur'
              }"
            >
              <el-input v-model="sale.total"></el-input>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
      <span slot="footer" class="dialog-footer">
        <el-button @click="cancel()">Cancelar</el-button>
        <el-button type="primary" @click="saveCar()" :loading="loading">Agregar</el-button>
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
      loading: false,
      brands: [],
      years: [],
      services: [],
      carServices: [-1],
      clients: [],
      users: [],
      sale: {
        brand: "",
        year: "",
        service: "",
        total: 0,
        user: "",
        client: ""
      },
      rules: {
        brand: [
          {
            required: true,
            message: "Campo Vehiculo es obligatorio",
            trigger: "change"
          }
        ],
        year: [
          {
            required: true,
            message: "Campo Año es obligatorio",
            trigger: "change"
          }
        ],
        service: [
          {
            required: true,
            message: "Campo Servicio es obligatorio",
            trigger: "change"
          }
        ],
        user: [
          {
            required: true,
            message: "Campo Empleado es obligatorio",
            trigger: "change"
          }
        ]
      }
    };
  },
  mounted() {
    var $this = this;

    $this.years = [];
    for (var year = 1999; year <= new Date().getFullYear(); year++) {
      $this.years.push(year);
    }

    axios.get("/api/services?all=1").then(function(response) {
      $this.services = response.data;
    });
  },
  computed: {
    showTotal: function() {
      var $this = this;
      if ($this.sale.service == "") {
        return false;
      }
      if (
        $this.carServices.length == 0 &&
        $this.sale.brand &&
        $this.sale.year
      ) {
        return true;
      }
      return false;
    }
  },
  methods: {
    changeCar() {
      var $this = this;
      if ($this.sale.brand && $this.sale.year) {
        axios
          .get("/api/servicesByCar", {
            params: {
              brand: $this.sale.brand,
              year: $this.sale.year
            }
          })
          .then(function(response) {
            $this.carServices = [];
            for (var i = 0; i < response.data.length; i++) {
              $this.carServices.push(response.data[i].id);
            }
          });
      }
    },
    handleClose(done) {
      var $this = this;
      if ($this.sale.brand) {
        $this
          .$confirm("¿Estas seguro de no guardar la Orden de Servicio?")
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
      this.$refs.saleForm.resetFields();
    },
    saveCar() {
      var $this = this;
      $this.$root.$emit("addService", $this.sale.service, $this.sale.total);
      $this.cancel();
      return;
      $this.$refs.saleForm.validate(valid => {
        if (valid) {
          $this.loading = true;
          axios
            .post("/api/sales", $this.sale)
            .then(function(response) {
              $this.$notify({
                title: "¡Exito!",
                message: "La Orden de Servicio fue agregado correctamente",
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
<style>
.popup {
  .el-input {
    width: 220px;
  }
}
</style>