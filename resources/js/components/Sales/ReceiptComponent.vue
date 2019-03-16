<template>
  <el-container>
    <el-main class="content">
      <el-row>
        <el-col :span="24">
          <el-form inline label-position="right" label-width="80px" class="query-form">
            <el-form-item label="Cliente" prop="client">
              <el-select
                filterable
                placeholder="Selecciona un Client"
                v-model="client"
                :disabled="sale != false"
              >
                <el-option
                  v-for="client in clients"
                  :key="client.id"
                  :label="client.name"
                  :value="client.id"
                ></el-option>
              </el-select>
            </el-form-item>
            <el-form-item style="float: right;" v-if="sale">
              <el-button icon="el-icon-printer" @click="buildReceipt()">Imprimir</el-button>
            </el-form-item>
          </el-form>
          <br>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="18" :offset="3">
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
                <el-row v-for="(service, index) in order.services" v-bind:key="index">
                  <el-col :span="22" :offset="2">
                    <h4>{{ service.label }}</h4>
                  </el-col>
                  <el-col :span="24">
                    <el-row
                      v-for="(item, index) in service.items"
                      v-bind:key="index"
                      class="row-item"
                    >
                      <el-col :span="6" :offset="4">{{ item.name }}</el-col>
                      <el-col :span="4">${{ formatPrice(item[order.price+"_price"]) }}</el-col>
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
              <el-col :span="3" :offset="8">${{ formatPrice(total) }}</el-col>
            </el-row>
          </el-card>
        </el-col>
      </el-row>
      <el-row type="flex" justify="end" v-if="!sale">
        <el-col :span="5" style="text-align:right;">
          <br>
          <el-button type="secondary" @click="back()">Regresar</el-button>
          <el-button type="primary" @click="save()">Guardar</el-button>
        </el-col>
      </el-row>
      <el-row type="flex" justify="end" style="opacity:0">
        <el-col :span="12">
          <img ref="receipt" src="/img/receipt.jpg" width="650px">
        </el-col>
        <el-col :span="12">
          <canvas ref="my-canvas"></canvas>
        </el-col>
      </el-row>
    </el-main>
  </el-container>
</template>

<script>
import { Printd } from "printd";

export default {
  props: ["sale"],
  data() {
    return {
      order: {},
      client: "",
      clients: [],
      context: ""
    };
  },
  mounted() {
    var $this = this;
    if (localStorage.getItem("order")) {
      try {
        $this.order = JSON.parse(localStorage.getItem("order"));
      } catch (e) {
        localStorage.removeItem("order");
      }
    }

    if ($this.sale) {
      var services = [];
      for (var i in $this.sale.services) {
        var items = [];
        for (var x in $this.sale.sale_services) {
          if ($this.sale.sale_services[x].item) {
            items.push({
              id: $this.sale.sale_services[x].item_id,
              name: $this.sale.sale_services[x].item.name,
              low_price: $this.sale.sale_services[x].price
            });
          }
        }
        services.push({
          id: $this.sale.services[i].id,
          label: $this.sale.services[i].name,
          items: items
        });
      }

      $this.order = {
        brand: $this.sale.car[0].brand,
        price: "low",
        services: services,
        year: $this.sale.sale_services[0].year
      };

      if ($this.sale.client) {
        $this.client = $this.sale.client.id;
      }

      axios.get("/api/clients?all=1").then(function(response) {
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
    buildReceipt() {
      var $this = this;

      $this.$refs["my-canvas"].width = $this.$refs["receipt"].width + 100;
      $this.$refs["my-canvas"].height = $this.$refs["receipt"].height;
      $this.context = $this.$refs["my-canvas"].getContext("2d");

      $this.context.clearRect(
        0,
        0,
        $this.$refs["my-canvas"].width,
        $this.$refs["my-canvas"].height
      );

      $this.context.fillStyle = "#FFFFFF";
      $this.context.fillRect(
        0,
        0,
        $this.$refs["my-canvas"].width,
        $this.$refs["my-canvas"].height
      );
      $this.context.drawImage(
        $this.$refs["receipt"],
        50,
        0,
        $this.$refs["receipt"].width,
        $this.$refs["my-canvas"].height
      );

      $this.sale.id;
      $this.context.font = "24px Georgia";
      $this.context.fillStyle = "red";
      $this.context.fillText($this.pad($this.sale.id, 5), 512, 60);

      $this.context.fillStyle = "black";
      $this.context.fillText(
        new Date()
          .toLocaleDateString("es-ES", {
            year: "numeric",
            month: "2-digit",
            day: "2-digit"
          })
          .replace("/", "-")
          .replace("/", "-"),
        487,
        125
      );

      $this.context.font = "16px Georgia";

      if ($this.sale.client) {
        if ($this.sale.client.name.length < 20) {
          $this.context.fillText($this.sale.client.name, 192, 358);
        } else {
          var length = $this.sale.client.name.length;
          var limit = $this.sale.client.name.substr(0, 20).lastIndexOf(" ");
          $this.context.fillText(
            $this.sale.client.name.substr(0, limit),
            192,
            352
          );
          $this.context.fillText(
            $this.sale.client.name.substr(limit + 1, length),
            192,
            364
          );
        }
        $this.context.fillText($this.sale.client.phonenumber, 128, 398);
      }

      $this.context.fillText($this.order.brand, 450, 338);
      $this.context.fillText($this.order.year, 450, 415);

      for (var s in $this.order.services) {
        $this.context.fillText("1", 92, 530 + s * 26);
        $this.context.fillText(
          $this.order.services[s].label,
          155,
          530 + s * 26
        );
        $this.context.fillText(
          "$" + $this.formatPrice($this.sumServiceTotal(s)),
          580,
          530 + s * 26
        );
      }

      $this.context.fillText("$" + $this.formatPrice($this.total), 580, 765);
      $this.context.fillText(
        "$" + $this.formatPrice($this.total * 0.08),
        580,
        797
      );
      $this.context.fillText(
        "$" + $this.formatPrice($this.total + $this.total * 0.08),
        580,
        833
      );

      var img = document.createElement("img");
      img.src = $this.$refs["my-canvas"].toDataURL("image/jpeg");
      img.width = $this.$refs["my-canvas"].width;
      img.height = $this.$refs["my-canvas"].height;

      const d = new Printd();
      d.print(img);
    },
    pad(number, size) {
      var s = String(number);
      while (s.length < (size || 2)) {
        s = "0" + s;
      }
      return s;
    },
    sumServiceTotal(i) {
      var total = 0;
      const order = this.order;

      for (var x in order.services[i].items) {
        if (order.services[i].items[x][order.price + "_price"]) {
          total += parseFloat(
            order.services[i].items[x][order.price + "_price"]
          );
        }
      }

      return total;
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

      for (var i in order.services) {
        for (var x in order.services[i].items) {
          if (order.services[i].items[x][order.price + "_price"]) {
            total += parseFloat(
              order.services[i].items[x][order.price + "_price"]
            );
          }
        }
      }

      return total;
    }
  }
};
</script>

<style lang="scss">
.box-card {
  max-height: 600px;
  overflow-y: auto;
}
.row-item:nth-child(odd) {
  background-color: #f2f2f2;
}
</style>
