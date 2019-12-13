<template>
  <div>
    <el-button type="primary" @click="dialogVisible = true" style="float:right;">Cotización Rapida</el-button>
    <el-dialog
      title="Cotización Rapida"
      :visible.sync="dialogVisible"
      width="800px"
      :before-close="handleClose"
    >
      <el-row>
        <el-form
          :label-position="labelPosition"
          :model="receipt"
          label-width="150px"
          ref="receiptForm"
        >
          <el-col :span="24">
            <el-row>
              <el-col :span="12">
                <el-form-item label="Nombre de Cliente" prop="name">
                  <el-input v-model="receipt.name"></el-input>
                </el-form-item>
              </el-col>
              <el-col :span="12">
                <el-form-item label="Telefono" prop="phonenumber">
                  <el-input v-model="receipt.phonenumber"></el-input>
                </el-form-item>
              </el-col>
            </el-row>
          </el-col>
          <el-col :span="6">
            <el-form-item label-width="50px" label="Marca" prop="maker">
              <el-input v-model="receipt.maker"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="6">
            <el-form-item label-width="45px" label="Año" prop="year">
              <el-input v-model="receipt.year"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="6">
            <el-form-item label-width="65px" label="Modelo" prop="model">
              <el-input v-model="receipt.model"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="6">
            <el-form-item label-width="55px" label="Color" prop="color">
              <el-input v-model="receipt.color"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-table :data="items" style="width: 100%" max-height="300px">
              <el-table-column label="Cantidad" width="80">
                <template slot-scope="scope">
                  <el-input v-model="scope.row.qty"></el-input>
                </template>
              </el-table-column>
              <el-table-column label="Descripcion" width="480">
                <template slot-scope="scope">
                  <el-input v-model="scope.row.description"></el-input>
                </template>
              </el-table-column>
              <el-table-column label="Importe" width="120">
                <template slot-scope="scope">
                  <el-input v-model="scope.row.cost"></el-input>
                </template>
              </el-table-column>
              <el-table-column width="80">
                <template slot="header">
                  <el-button @click.native.prevent="addRow(items)" type="text" size="small">Agregar</el-button>
                </template>
                <template slot-scope="scope">
                  <el-button
                    @click.native.prevent="deleteRow(scope.$index, items)"
                    type="text"
                    size="small"
                  >Eliminar</el-button>
                </template>
              </el-table-column>
            </el-table>
          </el-col>
        </el-form>
      </el-row>
      <span slot="footer" class="dialog-footer">
        <el-button @click="cancel()">Cancelar</el-button>
        <el-button type="primary" @click="saveReceipt()" :loading="loading">Generar Cotización</el-button>
      </span>
    </el-dialog>
    <el-row type="flex" justify="end" style="opacity: 0;overflow: hidden;height: 50px;">
      <el-col :span="12">
        <img ref="quick-receipt" src="/img/receipt.jpg" width="1200px" />
      </el-col>
      <el-col :span="12">
        <canvas ref="quick-canvas"></canvas>
      </el-col>
    </el-row>
  </div>
</template>
<script>
import { Printd } from "printd";

export default {
  data() {
    return {
      dialogVisible: false,
      loading: false,
      labelPosition: "left",
      items: [
        {
          qty: "",
          description: "",
          cost: ""
        }
      ],
      receipt: {
        name: "",
        phonenumber: "",
        maker: "",
        model: "",
        year: "",
        color: ""
      }
    };
  },
  methods: {
    deleteRow(index, rows) {
      rows.splice(index, 1);
    },
    addRow(rows) {
      rows.push({
        qty: "",
        description: "",
        cost: ""
      });
    },
    handleClose(done) {
      var $this = this;
      if ($this.receipt.name) {
        $this
          .$confirm("¿Estas seguro de no guardar el Rol?")
          .then(_ => {
            $this.cancel();
            done();
          })
          .catch(_ => {});
      } else {
        $this.cancel();
        done();
      }
    },
    cancel() {
      this.dialogVisible = false;
      this.loading = false;
      this.items = [
        {
          qty: "",
          description: "",
          cost: ""
        }
      ];
      this.items = this.$refs.receiptForm.resetFields();
    },
    formatPrice(value) {
      let val = (value / 1).toFixed(2);
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    },
    saveReceipt() {
      var $this = this;
      $this.$refs["quick-canvas"].width = $this.$refs["quick-receipt"].width;
      $this.$refs["quick-canvas"].height =
        $this.$refs["quick-receipt"].height - 109;
      $this.context = $this.$refs["quick-canvas"].getContext("2d");

      $this.context.clearRect(
        0,
        0,
        $this.$refs["quick-canvas"].width,
        $this.$refs["quick-canvas"].height
      );

      $this.context.fillStyle = "#FFFFFF";
      $this.context.fillRect(
        0,
        0,
        $this.$refs["quick-canvas"].width,
        $this.$refs["quick-canvas"].height
      );
      $this.context.drawImage(
        $this.$refs["quick-receipt"],
        -30,
        -30,
        $this.$refs["quick-receipt"].width,
        $this.$refs["quick-receipt"].height
      );

      $this.context.font = "24px Calibri";
      $this.context.fillStyle = "red";
      $this.context.fillText("00000", 950, 96);

      $this.context.fillStyle = "black";
      $this.context.fillText(
        new Date()
          .toLocaleDateString("es-ES", {
            year: "numeric",
            month: "2-digit",
            day: "2-digit"
          })
          .replace("/", "-")
          .replace("/", "-"),
        920,
        160
      );

      $this.context.font = "16px Calibri";

      if ($this.receipt.name) {
        $this.context.fillText($this.receipt.name, 233, 239);
      }
      if ($this.receipt.phonenumber) {
        $this.context.fillText($this.receipt.phonenumber, 150, 261);
      }
      // $this.context.fillText(currentSale.user.name, 237, 281);

      if ($this.receipt.maker) {
        $this.context.fillText($this.receipt.maker, 662, 240);
      }

      if ($this.receipt.model) {
        $this.context.fillText($this.receipt.model, 671, 262);
      }

      if ($this.receipt.year) {
        $this.context.fillText($this.receipt.year, 640, 283);
      }

      if ($this.receipt.color) {
        $this.context.fillText($this.receipt.color, 908, 240);
      }

      var total = 0;
      if ($this.items) {
        for (var x = 0; x < $this.items.length; x++) {
          total += parseFloat($this.items[x].cost);
          $this.context.fillText($this.items[x].qty, 100, 395 + x * 32);
          $this.context.fillText(
            $this.items[x].description.toUpperCase(),
            200,
            395 + x * 32
          );
          $this.context.fillText(
            $this.formatPrice($this.items[x].cost),
            928,
            395 + x * 32
          );
        }
      }

      $this.context.fillText("$" + $this.formatPrice(total), 928, 980);

      $this.context.fillText("$0", 928, 1011);

      $this.context.fillText("$" + $this.formatPrice(total), 928, 1042);

      var img = document.createElement("img");
      img.src = $this.$refs["quick-canvas"].toDataURL("image/jpeg");
      img.width = $this.$refs["quick-canvas"].width;
      img.height = $this.$refs["quick-canvas"].height;

      const d = new Printd();
      d.print(img);
    }
  }
};
</script>
<style lang="scss">
.el-checkbox {
  margin-right: 10px;
}
.el-checkbox + .el-checkbox {
  margin-left: 0;
}
.el-form-item__label {
  padding-left: 5px;
}
</style>

