<template>
  <el-row>
    <el-col :span="24">
      <el-table :data="users.data" class="table" stripe border style="width: 100%">
        <el-table-column prop="id" label="#" width="50px">
          <template slot-scope="scope">{{ scope.row.id }}</template>
        </el-table-column>
        <el-table-column prop="name" label="Nombre"></el-table-column>
        <el-table-column prop="email" label="Correo Electronico"></el-table-column>
        <el-table-column prop="created_at" label="Fecha"></el-table-column>
        <el-table-column>
          <template slot="header" slot-scope="scope">
            <el-input
              v-model="search"
              size="mini"
              placeholder="Escribe para buscar"
              :scope="scope"
            />
          </template>
          <template slot-scope="scope">
            <edit-users :user="scope.row" :roles="roles"></edit-users>
          </template>
        </el-table-column>
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
  props: ["roles"],
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
      this.loadTable("/api/users?page=" + this.page + "&search=" + this.search);
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
          "/api/users?page=" + $this.page + "&search=" + $this.search
        );
      }, 1000);
    }
  },
  data() {
    return {
      users: [],
      search: "",
      timeout: 0,
      page: 1
    };
  }
};
</script>
