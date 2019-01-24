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
          type="index"
        >
        </el-table-column>
        <el-table-column
          prop="name"
          label="Nombre"
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
  },
  methods: {
    loadTable(url) {
      var $this = this;
      axios.get(url).then(function(response) {
        $this.services = response.data;
      });
    },
    handleCurrentChange(val) {
      this.loadTable("/api/services?page=" + val);
    }
  },
  data() {
    return {
      services: []
    };
  }
};
</script>
