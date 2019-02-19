<template>
  <el-row>
    <el-col :span="24">
      <el-table :data="roles.data" class="table" stripe border style="width: 100%">
        <el-table-column prop="id" label="#" width="50px">
          <template slot-scope="scope">{{ scope.row.id }}</template>
        </el-table-column>
        <el-table-column prop="name" label="Nombre" width="150px"></el-table-column>
        <el-table-column label="Permisos">
          <template slot-scope="scope">{{ permissions(scope.row.permissions) }}</template>
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
  mounted: function() {
    this.loadTable("/api/roles");

    this.$root.$on("refreshTable", this.refreshTable);
  },
  methods: {
    loadTable(url) {
      var $this = this;
      axios.get(url).then(function(response) {
        $this.roles = response.data;
      });
    },
    refreshTable() {
      this.loadTable("/api/roles?page=" + this.page);
    },
    handleCurrentChange(val) {
      this.page = val;
      this.refreshTable();
    },
    permissions(permissions) {
      return permissions
        .map(function(el) {
          return el["name"];
        })
        .join(", ");
    }
  },
  data() {
    return {
      roles: [],
      page: 1
    };
  }
};
</script>
