<template>
  <el-container>
    <el-main class="content">
      <el-row>
        <el-col :span="7">
          <el-form inline label-position="right" label-width="80px" class="query-form">
            <el-form-item label="Vehiculo" prop="car">
              <el-select
                filterable
                placeholder="Selecciona un Vehiculo"
                v-model="car"
                :disabled="services.length == 0"
              >
                <el-option
                  v-for="car in cars"
                  :key="car.id"
                  :label="car.brand + ' (' + car.start_year + '-' + car.end_year + ')'"
                  :value="car.id"
                ></el-option>
              </el-select>
            </el-form-item>

            <el-form-item label="Servicio">
              <el-select
                filterable
                placeholder="Selecciona un Servicio"
                v-model="service"
                :disabled="services.length == 0"
              >
                <el-option
                  v-for="service in services"
                  :key="service.id"
                  :label="service.name"
                  :value="service.id"
                ></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Articulo">
              <el-select
                @change="handleChange"
                filterable
                placeholder="Selecciona un Articulo"
                v-model="item"
                :disabled="listItems.length == 0"
              >
                <el-option
                  v-for="(item, index) in listItems"
                  :key="index"
                  :label="item.name"
                  :value="index"
                ></el-option>
              </el-select>
            </el-form-item>
          </el-form>
        </el-col>
        <el-col :span="17" style="padding-left: 20px;">
          <h3>
            Articulos
            <div v-if="Object.keys(carService).length !== 0" style="float:right;">
              <el-checkbox v-model="updatePrices">Aplicar el porcentaje a todo la fila</el-checkbox>
            </div>
          </h3>
          <br>
          <el-card class="box-card">
            <el-row style="margin-bottom:5px;">
              <el-col :span="4">Nombre</el-col>
              <el-col :span="3">Base</el-col>
              <el-col :span="2">%</el-col>
              <el-col :span="3">Bajo</el-col>
              <el-col :span="2">%</el-col>
              <el-col :span="3">Medio</el-col>
              <el-col :span="2">%</el-col>
              <el-col :span="3">Alto</el-col>
            </el-row>
            <select-item ref="selectItem" :items="items" :updatePrices="updatePrices"></select-item>
          </el-card>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="17" :offset="7" style="padding-left: 20px;">
          <el-row style="padding: 10px 20px;">
            <el-col :span="4">
              <b>Total</b>
            </el-col>
            <el-col :span="3" style="text-align:right;">
              <b>${{ formatPrice(sumItemPrice("price")) }}</b>
            </el-col>
            <el-col :span="3" :offset="2" style="text-align:right;">
              <b>${{ formatPrice(sumItemPrice("low_price")) }}</b>
            </el-col>
            <el-col :span="3" :offset="2" style="text-align:right;">
              <b>${{ formatPrice(sumItemPrice("mid_price")) }}</b>
            </el-col>
            <el-col :span="3" :offset="2" style="text-align:right;">
              <b>${{ formatPrice(sumItemPrice("high_price")) }}</b>
            </el-col>
          </el-row>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="4" :offset="20" style="text-align:right;">
          <br>
          <el-button
            type="primary"
            :disabled="car == '' || service == '' || items.length == 0 || save"
            @click="next()"
          >Guardar</el-button>
        </el-col>
      </el-row>
    </el-main>
  </el-container>
</template>

<script>
export default {
  props: {
    carService: {
      type: Object,
      default: function() {
        return {};
      }
    },
    items: {
      type: Array,
      default: function() {
        return [];
      }
    }
  },
  watch: {
    filterText(val) {
      this.$refs.services.filter(val);
    }
  },
  data() {
    return {
      filterText: "",
      selectedServices: [],
      selectedPrice: "low",
      car: "",
      cars: [],
      service: "",
      services: [],
      item: "",
      listItems: [],
      save: false,
      updatePrices: true,
      defaultProps: {
        label: "label"
      }
    };
  },
  mounted: function() {
    const $this = this;

    axios.get("/api/cars?all=1").then(function(response) {
      $this.cars = response.data;
      if ($this.carService) {
        $this.car = $this.carService.car_id;
      }
    });

    axios.get("/api/services?all=1").then(function(response) {
      $this.services = response.data;
      if ($this.carService) {
        $this.service = $this.carService.service_id;
      }
    });

    axios.get("/api/items?all=1").then(function(response) {
      $this.listItems = response.data;
    });
  },
  methods: {
    handleChange(value) {
      this.item = "";
      var item = this.listItems[value];
      item.price = 0;
      item.low = 0;
      item.low_price = 0;
      item.mid = 0;
      item.mid_price = 0;
      item.high = 0;
      item.high_price = 0;
      this.items.push(item);
      this.$refs.selectItem.$forceUpdate();
    },
    formatPrice(value) {
      let val = (value / 1).toFixed(2);
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    },
    sumItemPrice(value) {
      var total = 0;
      if (this.items) {
        for (var item in this.items) {
          if (this.items[item][value]) {
            total += parseFloat(this.items[item][value]);
          }
        }
      }
      return total;
    },
    next() {
      var $this = this;
      axios
        .post("/api/carservices", {
          car: this.car,
          service: this.service,
          items: this.items
        })
        .then(function(response) {
          $this.save = true;
          $this.$notify({
            title: "¡Exito!",
            message: "Servicio fue agregado correctamente",
            type: "success"
          });
          setTimeout(function() {
            window.location.href = "/carservices";
          }, 1000);
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
    }
  }
};
</script>

<style lang="scss">
.box-card {
  min-height: 190px;
  max-height: 430px;
  overflow-y: auto;
  .el-tree {
    height: 350px;
    overflow-y: scroll;
    border: 1px solid rgb(242, 242, 242);
  }
}
.el-input.percentage {
  width: 50px;
  margin: 0 auto;
  display: block;
}
.el-input.price {
  width: 100%;
  input {
    text-align: right;
  }
}
.el-radio__label {
  display: none;
}
.price-selected {
  background: rgb(242, 242, 242);
}
</style>
