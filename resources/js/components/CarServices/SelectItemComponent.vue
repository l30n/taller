<template>
  <el-row>
    <el-row v-for="(item, index) in items" v-bind:key="index">
      <el-col :span="4">
        <label class="el-form-item__label">{{ item.name }}</label>
      </el-col>
      <el-col :span="3">
        <el-input class="price" v-model="item.price" @change="changeBase(item, index)"></el-input>
      </el-col>
      <el-col :span="2">
        <el-input
          class="percentage"
          maxlength="2"
          v-model="item.low"
          @change="changePercentage('low', item, index)"
        ></el-input>
      </el-col>
      <el-col :span="3">
        <el-input class="price" v-model="item.low_price"></el-input>
      </el-col>
      <el-col :span="2">
        <el-input
          class="percentage"
          maxlength="2"
          v-model="item.mid"
          @change="changePercentage('mid', item, index)"
        ></el-input>
      </el-col>
      <el-col :span="3">
        <el-input class="price" v-model="item.mid_price"></el-input>
      </el-col>
      <el-col :span="2">
        <el-input
          class="percentage"
          maxlength="2"
          v-model="item.high"
          @change="changePercentage('high', item, index)"
        ></el-input>
      </el-col>
      <el-col :span="3">
        <el-input class="price" v-model="item.high_price"></el-input>
      </el-col>
      <el-col :span="2">
        <el-button type="danger" icon="el-icon-delete" @click="deleteItem(index)"></el-button>
      </el-col>
    </el-row>
  </el-row>
</template>

<script>
export default {
  props: ["items"],
  mounted: function() {},
  methods: {
    formatPrice(value) {
      let val = (value / 1).toFixed(2);
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    },
    deleteItem(value) {
      this.items.splice(value, 1);
    },
    changeBase(item, index) {
      this.items[index].low_price = item.price;
      this.items[index].mid_price = item.price;
      this.items[index].high_price = item.price;
      this.$forceUpdate();
    },
    changePercentage(price, item, index) {
      this.items[index][price + "_price"] =
        parseInt(item.price) + (item.price * item[price]) / 100;
      this.$forceUpdate();
    }
  }
};
</script>

<style lang="scss">
.priceSelected {
  background: rgb(242, 242, 242);
  label {
    line-height: 37px;
  }
}
</style>
