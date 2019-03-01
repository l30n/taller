<template>
  <el-table :data="sales" border height="400" style="width: 100%">
    <el-table-column prop="date" label="Fecha"></el-table-column>
    <el-table-column label="Metodo de Pago">
      <template slot-scope="scope">{{ methods[scope.row.method] }}</template>
    </el-table-column>
    <el-table-column prop="orders" label="Servicios Terminados" width="170"></el-table-column>
    <el-table-column label="Total">
      <template slot-scope="scope">${{ formatPrice(scope.row.total) }}</template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  props: ["sales"],
  methods: {
    formatPrice(value) {
      let val = (value / 1).toFixed(2);
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    },
    refresh(sales) {
      this.sales = sales;
    }
  },
  data() {
    return {
      methods: { 1: "Efectivo", 2: "Electronico" }
    };
  }
};
</script>