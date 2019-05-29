<template>
  <el-container>
    <confirm-sales></confirm-sales>
    <el-main class="content">
      <el-row>
        <el-col :span="24">
          <el-form
            :rules="rules"
            :model="form"
            inline
            label-position="right"
            label-width="80px"
            class="query-form"
            ref="form"
          >
            <el-form-item label="Empleado" prop="user">
              <el-select
                filterable
                placeholder="Selecciona un Empleado"
                v-model="form.user"
                :disabled="currentSale != false"
              >
                <el-option v-for="user in users" :key="user.id" :label="user.name" :value="user.id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Cliente" prop="client">
              <el-select
                filterable
                placeholder="Selecciona un Client"
                v-model="form.client"
                :disabled="currentSale != false"
              >
                <el-option
                  v-for="client in clients"
                  :key="client.id"
                  :label="client.name"
                  :value="client.id"
                ></el-option>
              </el-select>
            </el-form-item>
            <el-form-item style="float: right;" v-if="currentSale">
              <el-button icon="el-icon-edit" @click="openConfirm()">Editar</el-button>
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
                    <el-row class="row-item" v-if="total != sumServiceTotal()">
                      <el-col :span="6" :offset="4">Otro</el-col>
                      <el-col :span="4">${{ formatPrice(total - sumServiceTotal()) }}</el-col>
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
      <el-row type="flex" justify="end" v-if="!currentSale">
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
import { setTimeout } from "timers";

export default {
  props: ["sale"],
  data() {
    return {
      order: {},
      currentSale: false,
      form: {
        client: "",
        user: ""
      },
      clients: [],
      users: [],
      rules: {
        user: [
          {
            required: true,
            message: "Empleado es obligatorio",
            trigger: "change"
          }
        ]
      },
      context: ""
    };
  },
  mounted() {
    var $this = this;
    this.$root.$on("refreshReceipt", this.refreshReceipt);
    if (localStorage.getItem("order")) {
      try {
        $this.order = JSON.parse(localStorage.getItem("order"));
      } catch (e) {
        localStorage.removeItem("order");
      }
    }

    if ($this.sale) {
      $this.currentSale = $this.sale;
      var services = [];
      for (var i in $this.currentSale.services) {
        var items = [];
        for (var x in $this.currentSale.sale_services) {
          if ($this.currentSale.sale_services[x].item) {
            items.push({
              id: $this.currentSale.sale_services[x].item_id,
              name: $this.currentSale.sale_services[x].item.name,
              low_price: $this.currentSale.sale_services[x].price
            });
          }
        }
        services.push({
          id: $this.currentSale.services[i].id,
          label: $this.currentSale.services[i].name,
          items: items
        });
      }

      $this.order = {
        brand: $this.currentSale.car[0].brand,
        price: "low",
        services: services,
        year: $this.currentSale.sale_services[0].year
      };
    }

    axios.get("/api/clients?all=1").then(function(response) {
      $this.clients = response.data;
      if ($this.currentSale) {
        if ($this.currentSale.client) {
          $this.form.client = $this.currentSale.client.id;
        }
      }
    });

    axios.get("/api/users?all=1&role=Empleado").then(function(response) {
      $this.users = response.data;
      if ($this.currentSale) {
        $this.form.user = $this.currentSale.user.id;
      }
    });
  },
  methods: {
    refreshReceipt(sale) {
      this.currentSale = sale;
    },
    openConfirm() {
      this.$root.$emit("confirmSale", this.currentSale, true);
    },
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

      $this.context.font = "24px Georgia";
      $this.context.fillStyle = "red";
      $this.context.fillText($this.pad($this.currentSale.id, 5), 512, 60);

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

      if ($this.currentSale.client) {
        if ($this.currentSale.client.name.length < 20) {
          $this.context.fillText($this.currentSale.client.name, 192, 358);
        } else {
          var length = $this.currentSale.client.name.length;
          var limit = $this.currentSale.client.name
            .substr(0, 20)
            .lastIndexOf(" ");
          $this.context.fillText(
            $this.currentSale.client.name.substr(0, limit),
            192,
            352
          );
          $this.context.fillText(
            $this.currentSale.client.name.substr(limit + 1, length),
            192,
            364
          );
        }
        $this.context.fillText($this.currentSale.client.phonenumber, 128, 398);
      }

      if ($this.currentSale.user.name.length < 20) {
        $this.context.fillText($this.currentSale.user.name, 195, 438);
      } else {
        var length = $this.currentSale.user.name.length;
        var limit = $this.currentSale.user.name.substr(0, 20).lastIndexOf(" ");
        $this.context.fillText(
          $this.currentSale.user.name.substr(0, limit),
          195,
          432
        );
        $this.context.fillText(
          $this.currentSale.user.name.substr(limit + 1, length),
          195,
          444
        );
      }

      $this.context.fillText($this.currentSale.maker, 450, 338);
      $this.context.fillText($this.currentSale.brand, 450, 378);
      $this.context.fillText($this.currentSale.year, 450, 415);
      $this.context.fillText($this.currentSale.color, 570, 338);

      $this.currentSale.total = parseFloat($this.currentSale.total);

      $this.context.fillText("1", 92, 530);
      $this.context.fillText($this.currentSale.concept, 155, 530);
      $this.context.fillText(
        "$" + $this.formatPrice($this.currentSale.total),
        580,
        530
      );

      $this.context.fillText($this.currentSale.details, 155, 555);

      if ($this.currentSale.guaranty) {
        $this.context.fillText($this.currentSale.guaranty, 160, 770);
      }

      $this.context.fillText(
        "$" + $this.formatPrice($this.currentSale.total),
        580,
        765
      );
      if ($this.currentSale.tax) {
        $this.context.fillText(
          "$" + $this.formatPrice($this.currentSale.total * 0.08),
          580,
          797
        );
      } else {
        $this.context.fillText("$0", 580, 797);
      }

      if ($this.currentSale.tax) {
        $this.context.fillText(
          "$" +
            $this.formatPrice(
              $this.currentSale.total + $this.currentSale.total * 0.08
            ),
          580,
          833
        );
      } else {
        $this.context.fillText(
          "$" + $this.formatPrice($this.currentSale.total),
          580,
          833
        );
      }

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
    sumServiceTotal() {
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
    },
    save() {
      var $this = this;
      $this.$refs.form.validate(valid => {
        if (valid) {
          $this.order.user = $this.form.user;
          if ($this.form.client) {
            $this.order.client = $this.form.client;
          }
          $this.order.total = $this.total;
          axios
            .post("/api/sales", $this.order)
            .then(function(response) {
              $this.$notify({
                title: "¡Exito!",
                message: "La Orden de Servicio fue agregada correctamente",
                type: "success"
              });
              setTimeout(function() {
                localStorage.removeItem("order");
                window.location.href = "/sales";
              }, 1500);
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
        }
      });
    }
  },
  computed: {
    total() {
      if (this.order.length == 0) {
        return 0;
      }

      if (this.currentSale) {
        return parseFloat(this.currentSale.total);
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
