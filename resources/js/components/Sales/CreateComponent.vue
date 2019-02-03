<template>
  <el-container>
    <el-main class="content">
      <el-row>
        <el-col :span="24">
          <el-form inline label-position="right" label-width="80px" class="query-form">
            <el-form-item label="Vehiculo" prop="brand">
              <el-select filterable placeholder="Selecciona un Vehiculo" v-model="brand">
                <el-option
                  v-for="brand in brands"
                  :key="brand.brand"
                  :label="brand.brand"
                  :value="brand.brand"
                ></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Año">
              <el-select
                v-model="year"
                filterable
                placeholder
                class="year"
                :disabled="years.length == 0"
              >
                <el-option v-for="year in years" :key="year" :label="year" :value="year"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item>
              <el-button
                type="primary"
                icon="el-icon-search"
                @click="search"
                :disabled="year == ''"
              >Buscar</el-button>
            </el-form-item>
          </el-form>
          <br>
        </el-col>
        <el-col :span="5">
          <h3>Servicios</h3>
          <el-card class="box-card">
            <el-input
              placeholder="Filtra servicios"
              v-model="filterText"
              v-if="services.length > 0"
            ></el-input>
            <el-tree
              class="filter-tree"
              node-key="id"
              v-model="service"
              :data="services"
              :props="defaultProps"
              default-expand-all
              show-checkbox
              :filter-node-method="filterNode"
              @check-change="handleServiceChange"
              ref="services"
              v-if="services.length > 0"
            ></el-tree>
            <el-row v-if="services.length == 0">
              <el-col :span="24" style="text-align: center;">No hay servicios</el-col>
            </el-row>
          </el-card>
        </el-col>
        <el-col :span="18" :offset="1">
          <h3>Articulos</h3>
          <el-card class="box-card">
            <el-row
              v-if="selectedServices.length > 0"
              style="text-align:center;margin-bottom:10px;"
            >
              <el-col :span="5" :offset="9">
                <el-radio label="low" v-model="selectedPrice"></el-radio>
              </el-col>
              <el-col :span="5">
                <el-radio label="mid" v-model="selectedPrice"></el-radio>
              </el-col>
              <el-col :span="5">
                <el-radio label="high" v-model="selectedPrice"></el-radio>
              </el-col>
            </el-row>
            <div v-for="(service, index) in selectedServices" v-bind:key="index">
              <select-service :service="service" :price="selectedPrice"></select-service>
            </div>
            <el-row v-if="selectedServices.length == 0">
              <el-col :span="24" style="text-align: center;">Servicio no seleccionado</el-col>
            </el-row>
          </el-card>
        </el-col>
      </el-row>
      <el-row type="flex" justify="end">
        <el-col :span="5" style="text-align:right;">
          <br>
          <el-button
            type="primary"
            icon="el-icon-arrow-right"
            :disabled="selectedServices.length == 0"
            @click="next()"
          >Continuar</el-button>
        </el-col>
      </el-row>
    </el-main>
  </el-container>
</template>

<script>
export default {
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
      brand: "",
      brands: [],
      year: "",
      years: [],
      service: "",
      services: [],
      defaultProps: {
        label: "label"
      }
    };
  },
  mounted: function() {
    const $this = this;

    $this.years = [];
    for (var year = 1999; year <= new Date().getFullYear(); year++) {
      $this.years.push(year);
    }

    axios.get("/api/car/brands").then(function(response) {
      $this.brands = response.data;
      if (localStorage.getItem("order") && $this.getParameter("back")) {
        var order = JSON.parse(localStorage.getItem("order"));
        $this.brand = order.brand;
        $this.year = order.year;
        $this.search();
      }
    });
  },
  methods: {
    filterNode(value, data) {
      if (!value) return true;
      return data.label.indexOf(value) !== -1;
    },
    search() {
      var $this = this;
      $this.selectedPrice = "low";
      $this.selectedServices = [];
      $this.services = [];
      axios
        .get("/api/servicesByCar", {
          params: {
            brand: $this.brand,
            year: $this.year
          }
        })
        .then(function(response) {
          for (var i = 0; i < response.data.length; i++) {
            $this.services.push({
              id: response.data[i].id,
              label: response.data[i].name,
              items: response.data[i].items
            });
          }
          if (localStorage.getItem("order") && $this.getParameter("back")) {
            var order = JSON.parse(localStorage.getItem("order"));
            setTimeout(function() {
              $this.$refs.services.setCheckedNodes(order.services);
              $this.selectedPrice = order.price;
            }, 0);
          }
        });
    },
    handleServiceChange(data, checked, indeterminate) {
      this.selectedServices = this.$refs.services.getCheckedNodes();
    },
    getParameter(name) {
      var result = null,
        tmp = [];
      location.search
        .substr(1)
        .split("&")
        .forEach(function(item) {
          tmp = item.split("=");
          if (tmp[0] === name) result = decodeURIComponent(tmp[1]);
        });
      return result;
    },
    next() {
      const order = {
        services: this.selectedServices,
        price: this.selectedPrice,
        brand: this.brand,
        year: this.year
      };
      const parsed = JSON.stringify(order);
      localStorage.setItem("order", parsed);
      window.location.href = "/sales/receipt";
    }
  }
};
</script>

<style lang="scss">
.box-card {
  height: 430px;
  overflow-y: auto;
  .el-tree {
    height: 350px;
    overflow-y: scroll;
    border: 1px solid rgb(242, 242, 242);
  }
}
.el-input.percentage {
  width: 50px;
}
.el-input.price {
  width: 80%;
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
.year {
  width: 80px;
}
</style>
