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
      @change="changeDate"
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
  methods: {
    changeDate: function(values) {
      var $this = this;
      axios
        .get(
          "api/dashboard?start=" +
            values[0].toISOString().substring(0, 10) +
            "&end=" +
            values[1].toISOString().substring(0, 10)
        )
        .then(function(response) {
          console.log($this.$root.$refs);
          $this.$root.$refs.dashboardNumbers.refresh(
            response.data.total,
            response.data.services
          );
          $this.$root.$refs.dashboardChart.refresh(response.data.salesByStatus);
          $this.$root.$refs.dashboardTable.refresh(response.data.sales);
        });
    }
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