<template>
  <el-row>
    <el-col :span="24">
      <el-table :data="users.data" class="table" stripe border style="width: 100%">
        <el-table-column prop="id" label="#" width="50px">
          <template slot-scope="scope">{{ scope.row.id }}</template>
        </el-table-column>
        <el-table-column prop="email" label="Correo Electronico"></el-table-column>
        <el-table-column prop="created_at" label="Fecha"></el-table-column>
      </el-table>
      <div class="block" style="text-align: center;" v-if="users.total > 10">
        <el-pagination
          layout="prev, pager, next"
          :page-size="10"
          :total="users.total"
          @current-change="handleCurrentChange"
        ></el-pagination>
      </div>
    </el-col>
  </el-row>
</template>

<script>
export default {
  mounted: function() {
    this.loadTable("/api/users");

    this.$root.$on("refreshTable", this.refreshTable);
  },
  methods: {
    loadTable(url) {
      var $this = this;
      axios.get(url).then(function(response) {
        $this.users = response.data;
      });
    },
    refreshTable() {
      this.loadTable("/api/users?page=" + this.page);
    },
    handleCurrentChange(val) {
      this.page = val;
      this.refreshTable();
    }
  },
  data() {
    return {
      users: [],
      page: 1
    };
  }
};
</script>
