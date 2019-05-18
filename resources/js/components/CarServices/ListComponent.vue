<template>
  <el-row>
    <el-col :span="24">
      <el-table
        v-loading="loading"
        :default-sort="{prop: 'id', order: 'ascending'}"
        :data="cars.filter(data => !search || (data.car.brand + ' ' + data.car.start_year + '-' + data.car.end_year + ' ' + data.service.name).toLowerCase().includes(search.toLowerCase())).splice((page - 1) * limit, limit)"
        class="table"
        stripe
        border
        style="width: 100%"
        @sort-change="changeSort"
      >
        <el-table-column prop="id" label="#" sortable="custom" width="70px">
          <template slot-scope="scope">{{ scope.row.id }}</template>
        </el-table-column>
        <el-table-column label="Marca" prop="car.name" sortable="custom">
          <template slot-scope="scope">{{ scope.row.car.brand }}</template>
        </el-table-column>
        <el-table-column label="Año" prop="car.start_year" sortable="custom">
          <template slot-scope="scope">{{ scope.row.car.start_year }}-{{ scope.row.car.end_year }}</template>
        </el-table-column>
        <el-table-column label="Servicio" prop="service.name" sortable="custom">
          <template slot-scope="scope">{{ scope.row.service.name }}</template>
        </el-table-column>
        <el-table-column width="280px">
          <template slot="header" slot-scope="scope">
            <el-input
              v-model="search"
              size="mini"
              placeholder="Escribe para buscar"
              :scope="scope"
            />
          </template>
          <template slot-scope="scope">
            <span>
              <el-tooltip class="item" effect="dark" content="Editar" placement="top">
                <el-button icon="el-icon-edit" @click="goto('/carservices/edit/' + scope.row.id)"></el-button>
              </el-tooltip>
            </span>
            <delete-carservices :carservice="scope.row"></delete-carservices>
          </template>
        </el-table-column>
      </el-table>
      <div class="block" style="text-align: center;" v-if="cars.length > limit && search == ''">
        <el-pagination
          layout="prev, pager, next"
          :page-size="limit"
          :total="cars.length"
          @current-change="handleCurrentChange"
        ></el-pagination>
      </div>
    </el-col>
  </el-row>
</template>

<script>
export default {
  mounted: function() {
    this.loadTable("/api/carservices?all=1");

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
      this.loadTable("/api/carservices?all=" + this.page);
    },
    handleCurrentChange(val) {
      this.page = val;
      //this.refreshTable();
    },
    changeSort(sort) {
      this.loadTable(
        "/api/carservices?all=" +
          this.page +
          "&sort=" +
          sort.prop +
          "&order=" +
          sort.order
      );
    },
    goto: function(link) {
      window.location.href = link;
    }
  },
  data() {
    return {
      cars: [],
      page: 1,
      limit: 10,
      search: "",
      loading: true
    };
  }
};
</script>
