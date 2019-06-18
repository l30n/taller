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
          <img ref="receipt" src="/img/receipt.jpg" width="1200px">
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

      $this.context.font = "24px Georgia";
      $this.context.fillStyle = "red";
      $this.context.fillText($this.pad(currentSale.id, 5), 950, 96);

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

      $this.context.font = "16px Georgia";

      if (currentSale.client) {
        $this.context.fillText(currentSale.client.name, 233, 239);
        $this.context.fillText(
          currentSale.phonenumber
            ? currentSale.phonenumber
            : currentSale.client.phonenumber,
          150,
          261
        );
      } else if (currentSale.phonenumber) {
        $this.context.fillText(currentSale.phonenumber, 150, 261);
      }
      $this.context.fillText(currentSale.user.name, 237, 281);

      $this.context.fillText(
        currentSale.maker ? currentSale.maker : currentSale.car[0].maker,
        662,
        240
      );
      $this.context.fillText(
        currentSale.brand ? currentSale.brand : currentSale.car[0].brand,
        671,
        262
      );
      $this.context.fillText(
        currentSale.year ? currentSale.year : currentSale.sale_services[0].year,
        640,
        283
      );
      $this.context.fillText(currentSale.color, 908, 240);
      $this.context.fillText(currentSale.last_service, 988, 262);
      $this.context.fillText(currentSale.km, 978, 283);

      currentSale.total = parseFloat(currentSale.total);

      $this.context.fillText("1", 100, 395);
      if (currentSale.concept) {
        var concept = currentSale.concept.match(/.{1,60}/g);
        for (var x = 0; x < concept.length; x++) {
          $this.context.fillText(concept[x].toUpperCase(), 200, 395 + x * 32);
        }
      }
      $this.context.fillText(
        "$" + $this.formatPrice(currentSale.total),
        928,
        395
      );

      if (currentSale.details) {
        var details = currentSale.details.match(/.{1,60}/g);
        for (var x = 0; x < details.length; x++) {
          $this.context.fillText(
            details[x].toUpperCase(),
            200,
            395 + 32 * concept.length + x * 32
          );
        }
      }

      $this.context.fillText(
        "$" + $this.formatPrice(currentSale.total),
        928,
        980
      );
      if (currentSale.tax) {
        $this.context.fillText(
          "$" + $this.formatPrice(currentSale.total * 0.08),
          928,
          1011
        );
      } else {
        $this.context.fillText("$0", 928, 1011);
      }

      if (currentSale.tax) {
        $this.context.fillText(
          "$" + $this.formatPrice(currentSale.total + currentSale.total * 0.08),
          928,
          1042
        );
      } else {
        $this.context.fillText(
          "$" + $this.formatPrice(currentSale.total),
          928,
          1042
        );
      }

      if (currentSale.guaranty) {
        $this.context.font = "24px Georgia";
        $this.context.fillText(currentSale.guaranty, 180, 1011);
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