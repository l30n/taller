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
    this.loadTable("/api/items");
  },
  methods: {
    loadTable(url) {
      var $this = this;
      axios.get(url).then(function(response) {
        $this.items = response.data;
      });
    },
    handleCurrentChange(val) {
      this.loadTable("/api/items?page=" + val);
    }
  },
  data() {
    return {
      items: []
    };
  }
};
</script>
