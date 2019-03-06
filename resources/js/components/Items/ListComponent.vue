<template>
  <el-row>
    <el-col :span="24">
      <el-table :data="items.data" class="table" stripe border style="width: 100%">
        <el-table-column prop="id" label="#" width="50px">
          <template slot-scope="scope">{{ scope.row.id }}</template>
        </el-table-column>
        <el-table-column prop="name" label="Articulo"></el-table-column>
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
            <edit-items :item="scope.row"></edit-items>
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
    this.loadTable("/api/items");

    this.$root.$on("refreshTable", this.refreshTable);
  },
  methods: {
    loadTable(url) {
      var $this = this;
      axios.get(url).then(function(response) {
        $this.items = response.data;
      });
    },
    refreshTable() {
      this.loadTable("/api/items?page=" + this.page + "&search=" + this.search);
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
          "/api/items?page=" + $this.page + "&search=" + $this.search
        );
      }, 1000);
    }
  },
  data() {
    return {
      items: [],
      search: "",
      timeout: 0,
      page: 1
    };
  }
};
</script>
