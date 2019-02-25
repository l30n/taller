<template>
  <el-row>
    <el-col :span="24">
      <el-table
        :data="data.filter(data => !search || data.car.brand.toLowerCase().includes(search.toLowerCase()))"
        class="table"
        stripe
        border
        style="width: 100%"
      >
        <el-table-column prop="id" label="#" width="50px">
          <template slot-scope="scope">{{ scope.row.id }}</template>
        </el-table-column>
        <el-table-column label="Marca">
          <template slot-scope="scope">{{ scope.row.car.brand }}</template>
        </el-table-column>
        <el-table-column label="Año">
          <template slot-scope="scope">{{ scope.row.car.start_year }}-{{ scope.row.car.end_year }}</template>
        </el-table-column>
        <el-table-column label="Servicio">
          <template slot-scope="scope">{{ scope.row.service.name }}</template>
        </el-table-column>
        <el-table-column>
          <template slot="header" slot-scope="scope">
            <el-input v-model="search" size="mini" placeholder="Escribe para buscar"/>
          </template>
          <template slot-scope="scope">
            <el-button icon="el-icon-edit" @click="goto('/carservices/edit/' + scope.row.id)">Editar</el-button>
          </template>
        </el-table-column>
      </el-table>
      <div class="block" style="text-align: center;" v-if="cars.length > 10">
        <el-pagination
          layout="prev, pager, next"
          :page-size="10"
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
  computed: {
    data: function() {
      console.log((this.page - 1) * 10);
      return this.cars.splice((this.page - 1) * 10, 10);
    }
  },
  methods: {
    loadTable(url) {
      var $this = this;
      axios.get(url).then(function(response) {
        $this.cars = response.data;
      });
    },
    refreshTable() {
      this.loadTable("/api/carservices?all=" + this.page);
    },
    handleCurrentChange(val) {
      this.page = val;
      //this.refreshTable();
    },
    goto: function(link) {
      window.location.href = link;
    }
  },
  data() {
    return {
      cars: [],
      page: 1,
      search: ""
    };
  }
};
</script>
