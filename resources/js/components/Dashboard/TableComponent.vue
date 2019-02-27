<template>
  <el-table :data="tableData" border height="400" style="width: 100%">
    <el-table-column prop="date" label="Fecha" width="180"></el-table-column>
    <el-table-column prop="sale" label="Servicios Completados" width="180"></el-table-column>
    <el-table-column label="Total">
      <template slot-scope="scope">${{ formatPrice(scope.row.total) }}</template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  data() {
    return {
      tableData: [
        {
          date: "2016-05-03",
          sale: 1,
          total: 10000
        },
        {
          date: "2016-05-02",
          sale: 2,
          total: 9500
        },
        {
          date: "2016-05-04",
          sale: 5,
          total: 17000
        },
        {
          date: "2016-05-01",
          sale: 7,
          total: 13000
        }
      ]
    };
  },
  methods: {
    formatPrice(value) {
      let val = (value / 1).toFixed(2);
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
  }
};
</script>