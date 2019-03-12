<template>
  <el-row>
    <el-col :span="24">
      <el-table
        v-loading="loading"
        :data="items.data"
        class="table"
        stripe
        border
        style="width: 100%"
      >
        <el-table-column prop="id" label="#" width="50px">
          <template slot-scope="scope">{{ scope.row.id }}</template>
        </el-table-column>
        <el-table-column prop="name" label="Nombre de Cliente"></el-table-column>
        <el-table-column prop="phonenumber" label="Telefono"></el-table-column>
        <el-table-column prop="address" label="Direccion"></el-table-column>
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
            <edit-clients :client="scope.row"></edit-clients>
            <delete-clients :client="scope.row"></delete-clients>
          </template>
        </el-table-column>
      </el-table>
      <div class="block" style="text-align: center;" v-if="items.total > 10">
        <el-pagination
          layout="prev, pager, next"
          :page-size="10"
          :total="items.total"
          @current-change="handleCurrentChange"
        ></el-pagination>
      </div>
    </el-col>
  </el-row>
</template>

<script>
export default {
  mounted: function() {
    this.loadTable("/api/clients");

    this.$root.$on("refreshTable", this.refreshTable);
  },
  methods: {
    loadTable(url) {
      var $this = this;
      $this.loading = true;
      axios.get(url).then(function(response) {
        $this.items = response.data;
        $this.loading = false;
      });
    },
    refreshTable() {
      this.loadTable(
        "/api/clients?page=" + this.page + "&search=" + this.search
      );
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
          "/api/clients?page=" + $this.page + "&search=" + $this.search
        );
      }, 1000);
    }
  },
  data() {
    return {
      items: [],
      search: "",
      timeout: 0,
      page: 1,
      loading: true
    };
  }
};
</script>
