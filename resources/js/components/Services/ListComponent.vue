<template>
  <el-row>
    <el-col :span="24">
      <el-table
        :data="services.data"
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
          label="Servicio"
        >
        </el-table-column>
      </el-table>
      <div
        class="block"
        style="text-align: center;"
        v-if="services.total > 10"
      >
        <el-pagination
          layout="prev, pager, next"
          :page-size="10"
          :total="services.total"
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
    this.loadTable("/api/services");

    this.$root.$on("refreshTable", this.refreshTable);
  },
  methods: {
    loadTable(url) {
      var $this = this;
      axios.get(url).then(function(response) {
        $this.services = response.data;
      });
    },
    refreshTable() {
      this.loadTable("/api/services?page=" + this.page);
    },
    handleCurrentChange(val) {
      this.page = val;
      this.refreshTable();
    }
  },
  data() {
    return {
      services: [],
      page: 1
    };
  }
};
</script>
