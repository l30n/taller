<template>
  <el-row type="flex">
    <el-col :span="18">
      <el-row type="flex">
        <el-col
          :span="6"
          style="text-align:center;"
          v-for="(status, index) in statuses"
          v-bind:key="index"
        >
          <span class="bigNumber">{{ services[index] ? services[index].length : 0 }}</span>
          <p :class="status.toLowerCase().replace(/\s+/g, '')">{{ status }}</p>
        </el-col>
      </el-row>
    </el-col>
    <el-col :span="6" style="text-align:center;">
      <span class="bigNumber">${{ formatPrice(total) }}</span>
      <p>Total</p>
    </el-col>
  </el-row>
</template>

<script>
export default {
  props: ["total", "services"],
  data() {
    return {
      statuses: ["Cotizacion", "En Proceso", "Terminado", "Cancelado"]
    };
  },
  mounted() {
    this.$on("refresh", this.refresh);
  },
  methods: {
    formatPrice(value) {
      let val = (value / 1).toFixed(2);
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    },
    refresh(total, services) {
      this.total = total;
      this.services = services;
    }
  }
};
</script>