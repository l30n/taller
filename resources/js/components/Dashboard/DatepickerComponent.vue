<template>
  <div class="block" style="float:right;">
    <span class="demonstration">Fecha:</span>
    <el-date-picker
      v-model="dateRange"
      type="daterange"
      range-separator="a"
      start-placeholder="Fecha de inicio"
      end-placeholder="Fecha final"
      :picker-options="pickerOptions"
    ></el-date-picker>
  </div>
</template>

<script>
export default {
  mounted: function() {
    const end = new Date();
    const start = new Date();
    start.setDate(end.getDate() - ((end.getDay() + 6) % 7));
    this.dateRange = [start, end];
  },
  data() {
    return {
      pickerOptions: {
        shortcuts: [
          {
            text: "Esta semana",
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setDate(end.getDate() - ((end.getDay() + 6) % 7));
              picker.$emit("pick", [start, end]);
            }
          },
          {
            text: "Semana pasada",
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
              picker.$emit("pick", [start, end]);
            }
          },
          {
            text: "Mes pasado",
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
              picker.$emit("pick", [start, end]);
            }
          },
          {
            text: "Ultimos 3 meses",
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
              picker.$emit("pick", [start, end]);
            }
          }
        ]
      },
      dateRange: ""
    };
  }
};
</script>