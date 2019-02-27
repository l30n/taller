<template>
  <el-row type="flex">
    <el-col :span="4" style="text-align:center;">
      <span class="bigNumber">${{ formatPrice(total) }}</span>
      <p>Total</p>
    </el-col>
    <el-col :span="20">
      <el-row type="flex">
        <el-col
          :span="6"
          style="text-align:center;"
          v-for="(status, index) in statuses"
          v-bind:key="index"
        >
          <span class="bigNumber">{{ sales[index] ? sales[index].length : 0 }}</span>
          <p :class="status.toLowerCase().replace(/\s+/g, '')">{{ status }}</p>
        </el-col>
      </el-row>
    </el-col>
  </el-row>
</template>

<script>
export default {
  props: ["total", "sales"],
  data() {
    return {
      statuses: ["Cotizacion", "En Proceso", "Terminado", "Cancelado"]
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