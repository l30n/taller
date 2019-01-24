<template>
  <el-row>
    <el-col :span="24">
      <el-table
        :data="cars.data"
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
        <el-table-column
          prop="brand"
          label="Marca"
        >
        </el-table-column>
        <el-table-column label="Año">
          <template slot-scope="scope">
            {{ scope.row.start_year }}-{{ scope.row.end_year }}
          </template>
        </el-table-column>
      </el-table>
      <div
        class="block"
        style="text-align: center;"
        v-if="cars.total > 10"
      >
        <el-pagination
          layout="prev, pager, next"
          :page-size="10"
          :total="cars.total"
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
    this.loadTable("/api/cars");
  },
  methods: {
    loadTable(url) {
      var $this = this;
      axios.get(url).then(function(response) {
        $this.cars = response.data;
      });
    },
    handleCurrentChange(val) {
      this.loadTable("/api/cars?page=" + val);
    }
  },
  data() {
    return {
      cars: []
    };
  }
};
</script>
