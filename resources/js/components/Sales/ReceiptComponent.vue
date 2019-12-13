<template>
  <el-container>
    <confirm-sales></confirm-sales>
    <el-main class="content">
      <el-row v-if="currentSale">
        <el-col :span="24">
          <el-form inline label-position="right" label-width="80px" class="query-form" ref="form">
            <el-form-item style="float: right;">
              <el-button icon="el-icon-edit" @click="openConfirm()">Editar</el-button>
              <el-button icon="el-icon-printer" @click="buildReceipt()">Imprimir</el-button>
            </el-form-item>
            <br style="clear:both;" />
          </el-form>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="7" v-if="!currentSale">
          <el-form
            :rules="rules"
            :model="form"
            label-position="right"
            label-width="115px"
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
            <el-form-item label="Telefono" prop="phoenumber">
              <el-input style="width: 220px;" v-model="form.phonenumber"></el-input>
            </el-form-item>
            <el-form-item label="Concepto" prop="concept">
              <el-input style="width: 220px;" v-model="form.concept"></el-input>
            </el-form-item>
            <el-form-item label="Color" prop="color">
              <el-input style="width: 220px;" v-model="form.color"></el-input>
            </el-form-item>
            <el-form-item label="Ultimo servicio" prop="last_service">
              <el-input style="width: 220px;" v-model="form.last_service"></el-input>
            </el-form-item>
            <el-form-item label="KM. de Ingreso" prop="km">
              <el-input style="width: 220px;" v-model="form.km"></el-input>
            </el-form-item>
            <br />
          </el-form>
        </el-col>
        <el-col :span="currentSale? 18:16" :offset="currentSale? 3:1">
          <br />
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
                    <br />
                  </el-col>
                </el-row>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="24">
                <hr />
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
          <br />
          <el-button type="secondary" @click="back()">Regresar</el-button>
          <el-button type="primary" @click="save()">Guardar</el-button>
        </el-col>
      </el-row>
      <el-row type="flex" justify="end" style="opacity: 0;overflow: hidden;height: 50px;">
        <el-col :span="12">
          <img ref="receipt" src="/img/receipt2.jpg" width="1200px" />
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
        user: "",
        phonenumber: "",
        concept: "",
        color: "",
        last_service: "",
        km: ""
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

      $this.$refs["my-canvas"].width = $this.$refs["receipt"].width;
      $this.$refs["my-canvas"].height = $this.$refs["receipt"].height - 109;
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
        -30,
        -30,
        $this.$refs["receipt"].width,
        $this.$refs["receipt"].height
      );

      $this.context.font = "24px Calibri";
      $this.context.fillStyle = "red";
      $this.context.fillText($this.pad($this.currentSale.id, 5), 950, 96);
      $this.context.fillText($this.pad($this.currentSale.id, 5), 950, 95 + 718);

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
        920,
        160
      );
      $this.context.fillText(
        new Date()
          .toLocaleDateString("es-ES", {
            year: "numeric",
            month: "2-digit",
            day: "2-digit"
          })
          .replace("/", "-")
          .replace("/", "-"),
        920,
        160 + 718
      );

      $this.context.font = "16px Calibri";

      if ($this.currentSale.client) {
        $this.context.fillText($this.currentSale.client.name, 233, 239);
        $this.context.fillText($this.currentSale.client.name, 233, 239 + 718);
        $this.context.fillText(
          $this.currentSale.phonenumber
            ? $this.currentSale.phonenumber
            : $this.currentSale.client.phonenumber,
          150,
          261
        );
        $this.context.fillText(
          $this.currentSale.phonenumber
            ? $this.currentSale.phonenumber
            : $this.currentSale.client.phonenumber,
          150,
          261 + 718
        );
      } else if ($this.currentSale.phonenumber) {
        $this.context.fillText($this.currentSale.phonenumber, 150, 261);
        $this.context.fillText($this.currentSale.phonenumber, 150, 261 + 718);
      }

      $this.context.fillText($this.currentSale.user.name, 237, 281);
      $this.context.fillText($this.currentSale.user.name, 237, 281 + 718);

      $this.context.fillText($this.currentSale.maker, 662, 240);
      $this.context.fillText($this.currentSale.maker, 662, 240 + 718);
      $this.context.fillText($this.currentSale.brand, 671, 262);
      $this.context.fillText($this.currentSale.brand, 671, 262 + 718);
      $this.context.fillText($this.currentSale.year, 640, 283);
      $this.context.fillText($this.currentSale.year, 640, 283 + 718);
      $this.context.fillText($this.currentSale.color, 908, 240);
      $this.context.fillText($this.currentSale.color, 908, 240 + 718);
      $this.context.fillText($this.currentSale.last_service, 988, 262);
      $this.context.fillText($this.currentSale.last_service, 988, 262 + 718);
      $this.context.fillText($this.currentSale.km, 978, 283);
      $this.context.fillText($this.currentSale.km, 978, 283 + 718);

      $this.currentSale.total = parseFloat($this.currentSale.total);

      $this.context.fillText("1", 100, 360);
      $this.context.fillText("1", 100, 360 + 718);
      if ($this.currentSale.concept) {
        var concept = $this.currentSale.concept.match(/.{1,60}/g);
        for (var x = 0; x < concept.length; x++) {
          $this.context.fillText(concept[x].toUpperCase(), 200, 360 + x * 32);
          $this.context.fillText(
            concept[x].toUpperCase(),
            200,
            360 + 718 + x * 32
          );
        }
      }
      $this.context.fillText(
        "$" + $this.formatPrice($this.currentSale.total),
        928,
        360
      );
      $this.context.fillText(
        "$" + $this.formatPrice($this.currentSale.total),
        928,
        360 + 718
      );

      if ($this.currentSale.details) {
        var details = $this.currentSale.details.match(/.{1,60}/g);
        for (var x = 0; x < details.length; x++) {
          $this.context.fillText(
            details[x].toUpperCase(),
            200,
            360 + 32 * concept.length + x * 32
          );
          $this.context.fillText(
            details[x].toUpperCase(),
            200,
            360 + 718 + 32 * concept.length + x * 32
          );
        }
      }

      $this.context.fillText(
        "$" + $this.formatPrice($this.currentSale.total),
        928,
        608
      );
      $this.context.fillText(
        "$" + $this.formatPrice($this.currentSale.total),
        928,
        608 + 718
      );
      if ($this.currentSale.tax) {
        $this.context.fillText(
          "$" + $this.formatPrice($this.currentSale.total * 0.08),
          928,
          640
        );
        $this.context.fillText(
          "$" + $this.formatPrice($this.currentSale.total * 0.08),
          928,
          640 + 718
        );
      } else {
        $this.context.fillText("$0", 928, 640);
        $this.context.fillText("$0", 928, 640 + 718);
      }

      if ($this.currentSale.tax) {
        $this.context.fillText(
          "$" +
            $this.formatPrice(
              $this.currentSale.total + $this.currentSale.total * 0.08
            ),
          928,
          672
        );
        $this.context.fillText(
          "$" +
            $this.formatPrice(
              $this.currentSale.total + $this.currentSale.total * 0.08
            ),
          928,
          672 + 718
        );
      } else {
        $this.context.fillText(
          "$" + $this.formatPrice($this.currentSale.total),
          928,
          672
        );
        $this.context.fillText(
          "$" + $this.formatPrice($this.currentSale.total),
          928,
          672 + 718
        );
      }

      if ($this.currentSale.guaranty) {
        $this.context.font = "24px Calibri";
        $this.context.fillText($this.currentSale.guaranty, 180, 618);
        $this.context.fillText($this.currentSale.guaranty, 180, 618 + 718);
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
          $this.order.phonenumber = $this.form.phonenumber;
          $this.order.concept = $this.form.concept;
          $this.order.color = $this.form.color;
          $this.order.last_service = $this.form.last_service;
          $this.order.km = $this.form.km;
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
