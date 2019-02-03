<template>
  <el-row>
    <el-col :span="24">
      <el-table
        :data="items.data"
        class="table"
        stripe
        border
        style="width: 100%"
      >
        <el-table-column
          prop="id"
          label="#"
          width="50px"
        >
          <template slot-scope="scope">
            {{ scope.row.id }}
          </template>
        </el-table-column>
        <el-table-column
          prop="name"
          label="Nombre de Cliente"
        >
        </el-table-column>
        <el-table-column
          prop="phonenumber"
          label="Telefono"
        >
        </el-table-column>
        <el-table-column
          prop="address"
          label="Direccion"
        >
        </el-table-column>
      </el-table>
      <div
        class="block"
        style="text-align: center;"
        v-if="items.total > 10"
      >
        <el-pagination
          layout="prev, pager, next"
          :page-size="10"
          :total="items.total"
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
    this.loadTable("/api/clients");

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
      this.loadTable("/api/clients?page=" + this.page);
    },
    handleCurrentChange(val) {
      this.page = val;
      this.refreshTable();
    }
  },
  data() {
    return {
      items: [],
      page: 1
    };
  }
};
</script>
