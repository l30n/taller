<template>
  <el-col :span="5">
    <el-button icon="el-icon-edit" @click="dialogVisible = true">Editar</el-button>
    <el-dialog
      title="Editar un Carro"
      :visible.sync="dialogVisible"
      width="40%"
      :before-close="handleClose"
    >
      <el-row>
        <el-col :span="24">
          <el-form
            :label-position="labelPosition"
            :rules="rules"
            :model="car"
            label-width="150px"
            ref="carForm"
          >
            <el-form-item label="Marca del Carro" prop="name">
              <el-input v-model="car.brand"></el-input>
            </el-form-item>
            <el-form-item :label="'Año (' + car.start_year + '-' + car.end_year + ')'">
              <el-slider
                v-model="car.year"
                range
                show-stops
                :min="1999"
                :max="2019"
                @change="changeYear"
              ></el-slider>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
      <span slot="footer" class="dialog-footer">
        <el-button @click="cancel()">Cancelar</el-button>
        <el-button type="primary" @click="saveCar()">Guardar</el-button>
      </span>
    </el-dialog>
  </el-col>
</template>
<script>
export default {
  props: ["car"],
  data() {
    return {
      dialogVisible: false,
      labelPosition: "left",
      rules: {
        brand: [
          {
            required: true,
            message: "Campo Marca es obligatorio",
            trigger: "change"
          }
        ]
      }
    };
  },
  watch: {
    car: function() {
      this.car.year = [this.car.start_year, this.car.end_year];
    }
  },
  methods: {
    handleClose(done) {
      var $this = this;
      if ($this.car.brand) {
        $this
          .$confirm("¿Estas seguro de no guardar el Carro?")
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
      this.$refs.carForm.resetFields();
    },
    saveCar() {
      var $this = this;
      $this.$refs.carForm.validate(valid => {
        if (valid) {
          axios
            .post("/api/cars", $this.car)
            .then(function(response) {
              $this.$notify({
                title: "¡Exito!",
                message: "El Carro fue editado correctamente",
                type: "success"
              });
              $this.$root.$emit("refreshTable");
              $this.cancel();
            })
            .catch(error => {
              if (error.response.data.errors) {
                var errors = error.response.data.errors;
                $this.$alert(errors[Object.keys(errors)[0]][0], "Error", {
                  confirmButtonText: "OK",
                  type: "error"
                });
              }
            });
        } else {
          return false;
        }
      });
    },
    changeYear(value) {
      this.car.start_year = value[0];
      this.car.end_year = value[1];
    }
  }
};
</script>
