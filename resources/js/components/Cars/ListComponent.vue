<template>
  <el-row>
    <el-col :span="24">
      <el-table
        v-loading="loading"
        :data="cars.data"
        class="table"
        stripe
        border
        style="width: 100%"
      >
        <el-table-column prop="id" label="#" width="50px">
          <template slot-scope="scope">{{ scope.row.id }}</template>
        </el-table-column>
        <el-table-column prop="maker" label="Marca"></el-table-column>
        <el-table-column prop="brand" label="Modelo"></el-table-column>
        <el-table-column label="Año">
          <template slot-scope="scope">{{ scope.row.start_year }}-{{ scope.row.end_year }}</template>
        </el-table-column>
        <el-table-column width="280px">
          <template slot="header" slot-scope="scope">
            <el-input
              v-model="search"
              size="mini"
              placeholder="Busca por marca de carro"
              :scope="scope"
            />
          </template>
          <template slot-scope="scope">
            <edit-cars :car="scope.row"></edit-cars>
            <delete-cars :car="scope.row"></delete-cars>
          </template>
        </el-table-column>
      </el-table>
      <div class="block" style="text-align: center;" v-if="cars.total > 10">
        <el-pagination
          layout="prev, pager, next"
          :page-size="10"
          :total="cars.total"
          @current-change="handleCurrentChange"
        ></el-pagination>
      </div>
    </el-col>
  </el-row>
</template>

<script>
export default {
  mounted: function() {
    this.loadTable("/api/cars");

    this.$root.$on("refreshTable", this.refreshTable);
  },
  methods: {
    loadTable(url) {
      var $this = this;
      $this.loading = true;
      axios.get(url).then(function(response) {
        $this.cars = response.data;
        $this.loading = false;
      });
    },
    refreshTable() {
      this.loadTable("/api/cars?page=" + this.page + "&search=" + this.search);
    },
    handleCurrentChange(val) {
      this.page = val;
      this.refreshTable();
    }
  },
  watch: {
    search: function() {
      var $this = this;
      if ($this.timeout) {
        clearTimeout($this.timeout);
      }
      $this.timeout = setTimeout(function() {
        $this.loadTable(
          "/api/cars?page=" + $this.page + "&search=" + $this.search
        );
      }, 1000);
    }
  },
  data() {
    return {
      cars: [],
      search: "",
      timeout: 0,
      page: 1,
      loading: true
    };
  }
};
</script>
