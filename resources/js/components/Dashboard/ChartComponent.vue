<script>
import { Bar } from "vue-chartjs";

export default {
  extends: Bar,
  data: () => ({
    data: {
      labels: ["2019-02-25", "2019-02-26"],
      datasets: [
        {
          label: "Cotizacion",
          backgroundColor: "#909399",
          data: [1, 1]
        },
        {
          label: "En Proceso",
          backgroundColor: "#e6a23c",
          data: [3, 4]
        },
        {
          label: "Terminado",
          backgroundColor: "#67c23a",
          data: [2, 1]
        },
        {
          label: "Cancelado",
          backgroundColor: "#f56c6c",
          data: [0, 1]
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        yAxes: [
          {
            stacked: true
          }
        ],
        xAxes: [
          {
            stacked: true
          }
        ]
      },
      legend: {
        display: false
      }
    }
  }),

  mounted() {
    this.renderChart(this.data, this.options);
  }
};
</script>