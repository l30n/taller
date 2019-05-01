<template>
  <el-row>
    <el-col :span="24">
      <el-table
        v-loading="loading"
        :data="sales.data"
        class="table"
        stripe
        border
        style="width: 100%"
      >
        <el-table-column prop="id" label="#" width="50px">
          <template slot-scope="scope">{{ scope.row.id }}</template>
        </el-table-column>
        <el-table-column label="Carro">
          <template
            slot-scope="scope"
          >{{ scope.row.car[0].brand }} {{ scope.row.sale_services[0].year ? scope.row.sale_services[0].year : scope.row.car[0].start_year +"-"+ scope.row.car[0].end_year }}</template>
        </el-table-column>
        <el-table-column label="Servicios">
          <template slot-scope="scope">
            {{ scope.row.services.name }}
            <ul>
              <li
                v-for="(service, index) in scope.row.services"
                v-bind:key="index"
              >{{ service.name }}</li>
            </ul>
          </template>
        </el-table-column>
        <el-table-column label="Cliente">
          <template slot-scope="scope">{{ scope.row.client ? scope.row.client.name : '' }}</template>
        </el-table-column>
        <el-table-column label="Estado">
          <template slot-scope="scope">
            <el-select
              v-model="scope.row.status"
              :class="status[scope.row.status].toLowerCase().replace(/\s+/g, '')"
              @change="handleChangeStatus(scope.$index)"
              :disabled="scope.row.status == 2"
            >
              <el-option
                v-for="(status, index) in status"
                :key="index"
                :label="status"
                :value="index"
                :disabled="index < scope.row.status"
                :class="status.toLowerCase().replace(/\s+/g, '')"
              >
                <span>{{ status }}</span>
              </el-option>
            </el-select>
          </template>
        </el-table-column>
        <el-table-column label="Total">
          <template slot-scope="scope">${{ formatPrice(scope.row.total) }}</template>
        </el-table-column>
        <el-table-column prop="created_at" label="Fecha"></el-table-column>
        <el-table-column width="80px">
          <template slot-scope="scope">
            <el-tooltip class="item" effect="dark" content="Recibo" placement="top">
              <el-button
                icon="el-icon-tickets"
                @click="goto('/sales/receipt/' + scope.row.id)"
                :disabled="scope.row.status != 2"
              ></el-button>
            </el-tooltip>
          </template>
        </el-table-column>
      </el-table>
      <div class="block" style="text-align: center;" v-if="sales.total > 10">
        <el-pagination
          layout="prev, pager, next"
          :page-size="10"
          :total="sales.total"
          @current-change="handleCurrentChange"
        ></el-pagination>
      </div>
      <confirm-sales></confirm-sales>
    </el-col>
  </el-row>
</template>

<script>
export default {
  mounted: function() {
    var $this = this;
    $this.loadTable("/api/sales");

    axios.get("/api/users?all=1&role=Empleado").then(function(response) {
      $this.users = response.data;
    });
  },
  methods: {
    loadTable(url) {
      var $this = this;
      $this.loading = true;
      axios.get(url).then(function(response) {
        $this.sales = response.data;
        $this.oldSales = JSON.parse(JSON.stringify(response.data));
        $this.loading = false;
      });
    },
    formatPrice(value) {
      let val = (value / 1).toFixed(2);
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    },
    handleCurrentChange(val) {
      this.loadTable("/api/sales?page=" + val);
    },
    handleChangeStatus(index) {
      var $this = this;
      var message = $this.$createElement(
        "p",
        null,
        "¿Quieres cambiar el estado la Orden de Servicio?"
      );
      $this.concept = "";
      $this.total = "";
      $this.user = "";
      var userName = "";
      if ($this.sales.data[index].status == 2) {
        $this.$root.$emit("confirmSale", $this.sales.data[index]);
        return;
        $this.total = $this.sales.data[index].total;
        $this.user = $this.sales.data[index].user_id;

        var options = [];
        for (var x = 0; x < $this.users.length; x++) {
          var option = $this.$createElement(
            "el-option",
            {
              props: {
                key: $this.users[x].id,
                value: $this.users[x].id
              }
            },
            $this.users[x].name
          );
          if ($this.users[x].id == $this.user) {
            userName = $this.users[x].name;
          }
          options.push(option);
        }

        var select = $this.$createElement(
          "el-select",
          {
            props: {
              model: $this.user,
              value: userName
            },
            model: {
              value: $this.user,
              callback: function(value) {
                $this.user = value;
                select.componentInstance.value = value;
                select.componentInstance.$forceUpdate();
              }
            }
          },
          options
        );

        var checkGroup = $this.$createElement(
          "el-radio-group",
          {
            model: {
              value: $this.method,
              callback: function(value) {
                checkGroup.componentInstance.value = value;
                $this.method = value;
              }
            },
            ref: "paymentMethod"
          },
          [
            $this.$createElement(
              "el-radio",
              {
                attrs: {
                  label: "1"
                }
              },
              "Efectivo"
            ),
            $this.$createElement(
              "el-radio",
              {
                attrs: {
                  label: "2"
                }
              },
              "Electronico"
            )
          ]
        );
        message = $this.$createElement("el-row", null, [
          $this.$createElement(
            "div",
            {
              class: "el-col el-col-24",
              style: {
                marginBottom: "10px"
              }
            },
            "¿Quieres cambiar el estado la Orden de Servicio?"
          ),
          $this.$createElement(
            "div",
            {
              class: "el-col el-col-8 el-form-item__label"
            },
            "Empleado:"
          ),
          $this.$createElement(
            "div",
            {
              class: "el-col el-col-16",
              style: {
                marginBottom: "4px"
              }
            },
            [select]
          ),
          $this.$createElement(
            "div",
            {
              class: "el-col el-col-8 el-form-item__label"
            },
            "Concepto:"
          ),
          $this.$createElement(
            "div",
            {
              class: "el-col el-col-16",
              style: {
                marginBottom: "4px"
              }
            },
            [
              $this.$createElement("el-input", {
                model: {
                  value: $this.concept,
                  callback: function(value) {
                    $this.concept = value;
                  }
                }
              })
            ]
          ),
          $this.$createElement(
            "div",
            {
              class: "el-col el-col-8 el-form-item__label"
            },
            "Mounto Total:"
          ),
          $this.$createElement(
            "div",
            {
              class: "el-col el-col-16",
              style: {
                marginBottom: "4px"
              }
            },
            [
              $this.$createElement("el-input", {
                model: {
                  value: $this.total,
                  callback: function(value) {
                    $this.total = value;
                  }
                }
              })
            ]
          ),
          $this.$createElement(
            "div",
            {
              class: "el-col el-col-8 el-form-item__label"
            },
            "Metodo de pago:"
          ),
          $this.$createElement(
            "div",
            {
              class: "el-col el-col-16",
              style: {
                paddingTop: "13px"
              }
            },
            [checkGroup]
          )
        ]);
      }
      $this
        .$msgbox({
          title: "Cambiar orden de estado",
          message: message,
          showCancelButton: true,
          confirmButtonText: "Si",
          cancelButtonText: "No"
        })
        .then(() => {
          axios
            .post("api/sales/status", {
              id: $this.sales.data[index].id,
              status: $this.sales.data[index].status,
              method: $this.method,
              concept: $this.concept,
              total: $this.total,
              user: $this.user
            })
            .then(function(response) {
              $this.oldSales = JSON.parse(JSON.stringify($this.sales));
            });
          $this.$message({
            type: "success",
            message: "Cambio de estatus exitoso"
          });
        })
        .catch(_ => {
          $this.sales = JSON.parse(JSON.stringify($this.oldSales));
        });
    },
    goto: function(link) {
      window.location.href = link;
    }
  },
  data() {
    return {
      sales: [],
      oldSales: [],
      users: [],
      status: ["Cotizacion", "En Proceso", "Terminado", "Cancelado"],
      concept: "",
      total: "",
      method: "2",
      user: "",
      loading: true
    };
  }
};
</script>
<style lang="scss">
.cotizacion {
  color: #909399;
  input {
    color: #909399;
  }
}
.enproceso {
  color: #e6a23c;
  input {
    color: #e6a23c;
  }
}
.terminado {
  color: #67c23a;
  input {
    color: #67c23a;
  }
}
.cancelado {
  color: #f56c6c;
  input {
    color: #f56c6c;
  }
}
</style>