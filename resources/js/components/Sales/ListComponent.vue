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
            <el-tooltip
              class="item"
              effect="dark"
              content="Cotizacion"
              placement="top"
              v-if="scope.row.status != 2"
            >
              <el-button icon="el-icon-tickets" @click="buildReceipt(scope.row)"></el-button>
            </el-tooltip>
            <el-tooltip
              class="item"
              effect="dark"
              content="Recibo"
              placement="top"
              v-if="scope.row.status == 2"
            >
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
      <el-row type="flex" justify="end" style="opacity: 0;overflow: hidden;height: 50px;">
        <el-col :span="12">
          <img ref="receipt" src="/img/receipt.jpg" width="650px">
        </el-col>
        <el-col :span="12">
          <canvas ref="my-canvas"></canvas>
        </el-col>
      </el-row>
    </el-col>
  </el-row>
</template>

<script>
import { Printd } from "printd";

export default {
  mounted: function() {
    this.loadTable("/api/sales");

    this.$root.$on("refreshTable", this.refreshTable);
  },
  methods: {
    refreshTable() {
      this.loadTable("/api/sales?page=" + this.page);
    },
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
      this.page = val;
      this.refreshTable();
    },
    handleChangeStatus(index) {
      var $this = this;
      var message = $this.$createElement(
        "p",
        null,
        "¿Quieres cambiar el estado la Orden de Servicio?"
      );

      if ($this.sales.data[index].status == 2) {
        $this.$root.$emit("confirmSale", $this.sales.data[index]);
        $this.sales = JSON.parse(JSON.stringify($this.oldSales));
        return false;
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
              status: $this.sales.data[index].status
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
    },
    pad(number, size) {
      var s = String(number);
      while (s.length < (size || 2)) {
        s = "0" + s;
      }
      return s;
    },
    buildReceipt(currentSale) {
      var $this = this;

      $this.$refs["my-canvas"].width = $this.$refs["receipt"].width * 1.2;
      $this.$refs["my-canvas"].height = $this.$refs["receipt"].height * 1.2;
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
        0,
        0,
        $this.$refs["receipt"].width * 1.2,
        $this.$refs["receipt"].height * 1.2
      );

      $this.context.font = "20px Georgia";
      $this.context.fillStyle = "red";
      $this.context.fillText($this.pad(currentSale.id, 5), 630, 85);

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
        605,
        125
      );

      $this.context.font = "12px Georgia";

      if (currentSale.client) {
        $this.context.fillText(currentSale.client.name, 175, 176);
        $this.context.fillText(
          currentSale.phonenumber
            ? currentSale.phonenumber
            : currentSale.client.phonenumber,
          115,
          189
        );
      } else if (currentSale.phonenumber) {
        $this.context.fillText(currentSale.phonenumber, 115, 189);
      }
      $this.context.fillText(currentSale.user.name, 175, 203);

      $this.context.fillText(
        currentSale.maker ? currentSale.maker : currentSale.car[0].maker,
        448,
        176
      );
      $this.context.fillText(
        currentSale.brand ? currentSale.brand : currentSale.car[0].brand,
        455,
        189
      );
      $this.context.fillText(
        currentSale.year ? currentSale.year : currentSale.sale_services[0].year,
        435,
        203
      );
      $this.context.fillText(currentSale.color, 610, 176);
      $this.context.fillText(currentSale.last_service, 660, 189);
      $this.context.fillText(currentSale.km, 655, 203);

      currentSale.total = parseFloat(currentSale.total);

      $this.context.fillText("1", 80, 277);
      $this.context.fillText(currentSale.concept, 150, 277);
      $this.context.fillText(
        "$" + $this.formatPrice(currentSale.total),
        615,
        277
      );

      $this.context.fillText(currentSale.details, 150, 297);

      $this.context.fillText(
        "$" + $this.formatPrice(currentSale.total),
        615,
        655
      );
      if (currentSale.tax) {
        $this.context.fillText(
          "$" + $this.formatPrice(currentSale.total * 0.08),
          615,
          675
        );
      } else {
        $this.context.fillText("$0", 615, 675);
      }

      if (currentSale.tax) {
        $this.context.fillText(
          "$" + $this.formatPrice(currentSale.total + currentSale.total * 0.08),
          615,
          695
        );
      } else {
        $this.context.fillText(
          "$" + $this.formatPrice(currentSale.total),
          615,
          695
        );
      }

      if (currentSale.guaranty) {
        $this.context.font = "20px Georgia";
        $this.context.fillText(currentSale.guaranty, 150, 680);
      }

      var img = document.createElement("img");
      img.src = $this.$refs["my-canvas"].toDataURL("image/jpeg");
      img.width = $this.$refs["my-canvas"].width;
      img.height = $this.$refs["my-canvas"].height;

      const d = new Printd();
      d.print(img);
    }
  },
  data() {
    return {
      sales: [],
      oldSales: [],
      status: ["Cotizacion", "En Proceso", "Terminado", "Cancelado"],
      loading: true,
      page: 1
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