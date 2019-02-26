<template>
  <el-row>
    <el-col :span="24">
      <el-table :data="sales.data" class="table" stripe border style="width: 100%">
        <el-table-column prop="id" label="#" width="50px">
          <template slot-scope="scope">{{ scope.row.id }}</template>
        </el-table-column>
        <el-table-column label="Carro">
          <template
            slot-scope="scope"
          >{{ scope.row.car[0].brand }} {{ scope.row.sale_service.year ? scope.row.sale_service.year : scope.row.car[0].start_year +"-"+ scope.row.car[0].end_year }}</template>
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
            >
              <el-option
                v-for="(status, index) in status"
                :key="index"
                :label="status"
                :value="index"
                :disabled="index < scope.row.status || scope.row.status == 2"
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
      </el-table>
      <div class="block" style="text-align: center;" v-if="sales.total > 10">
        <el-pagination
          layout="prev, pager, next"
          :page-size="10"
          :total="sales.total"
          @current-change="handleCurrentChange"
        ></el-pagination>
      </div>
    </el-col>
  </el-row>
</template>

<script>
export default {
  mounted: function() {
    this.loadTable("/api/sales");
  },
  methods: {
    loadTable(url) {
      var $this = this;
      axios.get(url).then(function(response) {
        $this.sales = response.data;
        $this.oldSales = JSON.parse(JSON.stringify(response.data));
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
      $this
        .$confirm(
          "¿Quieres cambiar el estados de la Orden de Servicio?",
          "Warning",
          {
            confirmButtonText: "Si",
            cancelButtonText: "No",
            type: "warning"
          }
        )
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
    }
  },
  data() {
    return {
      sales: [],
      oldSales: [],
      status: ["Cotizacion", "En Proceso", "Terminado", "Cancelado"]
    };
  }
};
</script>
<style lang="scss">
.cotizacion {
  background-color: #909399;
  input {
    background-color: #909399;
  }
}
.enproceso {
  background-color: #e6a23c;
  input {
    background-color: #e6a23c;
  }
}
.terminado {
  background-color: #67c23a;
  input {
    background-color: #67c23a;
  }
}
.cancelado {
  background-color: #f56c6c;
  input {
    background-color: #f56c6c;
  }
}
</style>