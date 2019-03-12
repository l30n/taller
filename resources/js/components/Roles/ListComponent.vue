<template>
  <el-row>
    <el-col :span="24">
      <el-table
        v-loading="loading"
        :data="roles.data"
        class="table"
        stripe
        border
        style="width: 100%"
      >
        <el-table-column prop="id" label="#" width="50px">
          <template slot-scope="scope">{{ scope.row.id }}</template>
        </el-table-column>
        <el-table-column prop="name" label="Nombre" width="150px"></el-table-column>
        <el-table-column label="Permisos">
          <template slot-scope="scope">{{ getPermissions(scope.row.permissions) }}</template>
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
            <edit-roles :role="scope.row" :permissions="permissions"></edit-roles>
            <delete-roles :role="scope.row"></delete-roles>
          </template>
        </el-table-column>
      </el-table>
      <div class="block" style="text-align: center;" v-if="roles.total > 10">
        <el-pagination
          layout="prev, pager, next"
          :page-size="10"
          :total="roles.total"
          @current-change="handleCurrentChange"
        ></el-pagination>
      </div>
    </el-col>
  </el-row>
</template>

<script>
export default {
  props: ["permissions"],
  mounted: function() {
    this.loadTable("/api/roles");

    this.$root.$on("refreshTable", this.refreshTable);
  },
  methods: {
    loadTable(url) {
      var $this = this;
      $this.loading = true;
      axios.get(url).then(function(response) {
        $this.roles = response.data;
        $this.loading = false;
      });
    },
    refreshTable() {
      this.loadTable("/api/roles?page=" + this.page + "&search=" + this.search);
    },
    handleCurrentChange(val) {
      this.page = val;
      this.refreshTable();
    },
    getPermissions(permissions) {
      return permissions
        .map(function(el) {
          return el["name"];
        })
        .join(", ");
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
          "/api/roles?page=" + $this.page + "&search=" + $this.search
        );
      }, 1000);
    }
  },
  data() {
    return {
      roles: [],
      search: "",
      timeout: 0,
      page: 1,
      loading: true
    };
  }
};
</script>
