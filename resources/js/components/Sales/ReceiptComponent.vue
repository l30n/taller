<template>
  <el-container>
    <el-main class="content">
      <el-row>
        <el-col :span="24">
          <el-form
            inline
            label-position="right"
            label-width="80px"
            class="query-form"
          >
            <el-form-item
              label="Cliente"
              prop="client"
            >
              <el-select
                filterable
                placeholder="Selecciona un Client"
                v-model="client"
              >
                <el-option
                  v-for="client in clients"
                  :key="client.id"
                  :label="client.name"
                  :value="client.id"
                >
                </el-option>
              </el-select>
            </el-form-item>
          </el-form>
          <br>
        </el-col>
      </el-row>
      <el-row>
        <el-col
          :span="18"
          :offset="3"
        >
          <el-card class="box-card">
            <el-row>
              <el-col :span="24">
                <el-row>
                  <el-col :span="6">
                    <h3>{{ order.brand }}</h3>
                  </el-col>
                  <el-col :span="4">
                    <h3>{{ order.year }}</h3>
                  </el-col>
                </el-row>
                <el-row
                  v-for="(service, index) in order.services"
                  v-bind:key="index"
                >
                  <el-col
                    :span="22"
                    :offset="2"
                  >
                    <h4>{{ service.label }}</h4>
                  </el-col>
                  <el-col :span="24">
                    <el-row
                      v-for="(item, index) in service.items"
                      v-bind:key="index"
                      :class="index % 2 == 0 ? 'odd':'even'"
                    >
                      <el-col
                        :span="6"
                        :offset="4"
                      >
                        {{ item.name }}
                      </el-col>
                      <el-col :span="4">
                        ${{ formatPrice(item.price + (item.price * item[order.price]/ 100)) }}
                      </el-col>
                    </el-row>
                    <br>
                  </el-col>
                </el-row>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="24">
                <hr>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="2">
                <h4>Total</h4>
              </el-col>
              <el-col
                :span="3"
                :offset="8"
              >${{ formatPrice(total) }}</el-col>
            </el-row>
          </el-card>
        </el-col>
      </el-row>
      <el-row
        type="flex"
        justify="end"
      >
        <el-col
          :span="5"
          style="text-align:right;"
        >
          <br>
          <el-button
            type="secondary"
            @click="back()"
          >
            Regresar
          </el-button>
          <el-button
            type="primary"
            @click="save()"
          >
            Guardar
          </el-button>
        </el-col>
      </el-row>
    </el-main>
  </el-container>
</template>

<script>
export default {
  data() {
    return {
      order: [],
      client: "",
      clients: []
    };
  },
  mounted() {
    if (localStorage.getItem("order")) {
      try {
        this.order = JSON.parse(localStorage.getItem("order"));
      } catch (e) {
        localStorage.removeItem("order");
      }
      var $this = this;
      axios.get("/api/clients").then(function(response) {
        $this.clients = response.data;
      });
    }
  },
  methods: {
    formatPrice(value) {
      let val = (value / 1).toFixed(2);
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    },
    back() {
      const parsed = JSON.stringify(this.order);
      localStorage.setItem("order", parsed);
      window.location.href = "/sales/create?back=1";
    },
    save() {
      localStorage.removeItem("order");
      if (this.client) {
        this.order.client = this.client;
      }
      this.order.total = this.total;
      axios.post("/api/sales", this.order).then(function(response) {
        window.location.href = "/sales";
      });
    }
  },
  computed: {
    total() {
      if (this.order.length == 0) {
        return 0;
      }

      var total = 0;
      const order = this.order;

      for (var i = 0; i < order.services.length; i++) {
        for (var x = 0; x < order.services[i].items.length; x++) {
          total +=
            order.services[i].items[x].price +
            (order.services[i].items[x].price *
              order.services[i].items[x][order.price]) /
              100;
        }
      }

      return total;
    }
  }
};
</script>

<style lang="scss">
.box-card {
  max-height: 500px;
  overflow-y: auto;
}
.odd {
  background-color: #f2f2f2;
}
</style>
