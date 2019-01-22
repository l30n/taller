<template>
  <el-row>
    <el-col :span="24">
      <el-table
        :data="sales.data"
        class="table"
        stripe
        border
        style="width: 100%"
      >
        <el-table-column
          prop="id"
          type="index"
        >
        </el-table-column>
        <el-table-column label="Carro">
          <template slot-scope="scope">
            {{ scope.row.car[0].brand }} {{ scope.row.car[0].start_year }}-{{ scope.row.car[0].end_year }}
          </template>
        </el-table-column>
        <el-table-column label="Servicios">
          <template slot-scope="scope">
            {{ scope.row.services.name }}
            <ul>
              <li
                v-for="(service, index) in scope.row.services"
                v-bind:key="index"
              >
                {{ service.name }}
              </li>
            </ul>
          </template>
        </el-table-column>
        <el-table-column label="Cliente">
          <template slot-scope="scope">{{ scope.row.client ? scope.row.client.name : '' }}</template>
        </el-table-column>
        <el-table-column label="Total">
          <template slot-scope="scope">${{ formatPrice(scope.row.total) }}</template>
        </el-table-column>
        <el-table-column
          prop="created_at"
          label="Fecha"
        >
        </el-table-column>
      </el-table>
      <div
        class="block"
        style="text-align: center;"
        v-if="sales.total > 10"
      >
        <el-pagination
          layout="prev, pager, next"
          :page-size="10"
          :total="sales.total"
          @current-change="handleCurrentChange"
        >
        </el-pagination>
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
      });
    },
    formatPrice(value) {
      let val = (value / 1).toFixed(2);
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    },
    handleCurrentChange(val) {
      this.loadTable("/api/sales?page=" + val);
    }
  },
  data() {
    return {
      sales: []
    };
  }
};
</script>
