<script>
import { Bar } from "vue-chartjs";

export default {
  props: ["sales"],
  extends: Bar,
  data: () => ({
    data: {
      labels: ["Cotizacion", "En Proceso", "Terminado", "Cancelado"],
      datasets: []
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      legend: {
        display: false
      },
      scales: {
        yAxes: [
          {
            ticks: {
              beginAtZero: true,
              callback: function(value) {
                var ranges = [
                  { divider: 1e6, suffix: "M" },
                  { divider: 1e3, suffix: "k" }
                ];
                function formatNumber(n) {
                  for (var i = 0; i < ranges.length; i++) {
                    if (n >= ranges[i].divider) {
                      return (
                        (n / ranges[i].divider).toString() + ranges[i].suffix
                      );
                    }
                  }
                  return n;
                }
                return "$" + formatNumber(value);
              }
            }
          }
        ]
      },
      tooltips: {
        enabled: true,
        mode: "single",
        callbacks: {
          label: function(tooltipItems, data) {
            function formatPrice(value) {
              let val = (value / 1).toFixed(2);
              return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
            return "$" + formatPrice(tooltipItems.yLabel);
          }
        }
      }
    }
  }),
  mounted() {
    this.data.datasets = [];
    if (this.sales.length > 0) {
      this.data.datasets.push({
        label: "Ordenes de Servicio",
        backgroundColor: ["#909399", "#e6a23c", "#67c23a", "#f56c6c"],
        data: [0, 0, 0, 0]
      });
      for (var label in this.data.labels) {
        for (var sale in this.sales) {
          if (this.sales[sale].status == label) {
            this.data.datasets[0].data[label] = parseFloat(
              this.sales[sale].total
            );
          }
        }
      }
    }
    this.renderChart(this.data, this.options);
  },
  methods: {
    refresh(sales) {
      this.data.datasets = [];
      if (sales.length > 0) {
        this.data.datasets.push({
          label: "Ordenes de Servicio",
          backgroundColor: ["#909399", "#e6a23c", "#67c23a", "#f56c6c"],
          data: [0, 0, 0, 0]
        });
        for (var label in this.data.labels) {
          for (var sale in sales) {
            if (sales[sale].status == label) {
              this.data.datasets[0].data[label] = parseFloat(sales[sale].total);
            }
          }
        }
      }
      this.renderChart(this.data, this.options);
    }
  }
};
</script>