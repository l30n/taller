<template>
  <el-row style="border-bottom: 1px solid black;margin-bottom:5px;">
    <el-row>
      <el-col :span="6">{{ service.label }}</el-col>
      <el-col :span="3">Base</el-col>
      <el-col :span="2" style="text-align:center;" v-bind:class="{ priceSelected: price == 'low'}">%</el-col>
      <el-col
        :span="3"
        style="text-align:center;"
        v-bind:class="{ priceSelected: price == 'low'}"
      >Bajo</el-col>
      <el-col :span="2" style="text-align:center;" v-bind:class="{ priceSelected: price == 'mid'}">%</el-col>
      <el-col
        :span="3"
        style="text-align:center;"
        v-bind:class="{ priceSelected: price == 'mid'}"
      >Medio</el-col>
      <el-col
        :span="2"
        style="text-align:center;"
        v-bind:class="{ priceSelected: price == 'high'}"
      >%</el-col>
      <el-col
        :span="3"
        style="text-align:center;"
        v-bind:class="{ priceSelected: price == 'high'}"
      >Alto</el-col>
    </el-row>
    <el-row v-for="(item, index) in service.items" v-bind:key="index">
      <el-col :span="1">
        <button
          class="el-icon-close"
          @click="deleteItem(index)"
          style="cursor:pointer;border:none;background-color:white;"
        ></button>
      </el-col>
      <el-col :span="5">
        <label class="el-form-item__label">{{ item.name }}</label>
      </el-col>
      <el-col :span="3">
        <el-input class="price" v-model="item.price" @change="changeBase(item, index)"></el-input>
      </el-col>
      <el-col :span="2" style="text-align:center;" v-bind:class="{ priceSelected: price == 'low'}">
        <el-input
          class="percentage"
          maxlength="2"
          v-model="item.low"
          @change="changePercentage('low', item, index)"
        ></el-input>
      </el-col>
      <el-col :span="3" style="text-align:center;" v-bind:class="{ priceSelected: price == 'low'}">
        <el-input class="price" v-model="item.low_price" @change="onChangePrice()"></el-input>
      </el-col>
      <el-col :span="2" style="text-align:center;" v-bind:class="{ priceSelected: price == 'mid'}">
        <el-input
          class="percentage"
          maxlength="2"
          v-model="item.mid"
          @change="changePercentage('mid', item, index)"
        ></el-input>
      </el-col>
      <el-col :span="3" style="text-align:center;" v-bind:class="{ priceSelected: price == 'mid'}">
        <el-input class="price" v-model="item.mid_price" @change="onChangePrice()"></el-input>
      </el-col>
      <el-col :span="2" style="text-align:center;" v-bind:class="{ priceSelected: price == 'high'}">
        <el-input
          class="percentage"
          maxlength="2"
          v-model="item.high"
          @change="changePercentage('high', item, index)"
        ></el-input>
      </el-col>
      <el-col :span="3" style="text-align:center;" v-bind:class="{ priceSelected: price == 'high'}">
        <el-input class="price" v-model="item.high_price" @change="onChangePrice()"></el-input>
      </el-col>
    </el-row>
    <el-row></el-row>
  </el-row>
</template>

<script>
export default {
  props: ["service", "price", "updatePrices"],
  data() {
    return {
      percentages: []
    };
  },
  methods: {
    formatPrice(value) {
      let val = (value / 1).toFixed(2);
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    },
    deleteItem(index) {
      delete this.service.items[index];
      this.onChangePrice();
    },
    changePercentage(price, item, index) {
      if (this.updatePrices) {
        for (var x in this.service.items) {
          if (this.service.items[x][price] != 0) {
            this.service.items[x][price + "_price"] =
              parseInt(this.service.items[x].price) +
              (this.service.items[x].price * item[price]) / 100;
            this.service.items[x][price] = item[price];
          }
        }
      } else {
        this.service.items[index][price + "_price"] =
          parseInt(item.price) + (item.price * item[price]) / 100;
      }
      this.onChangePrice();
    },
    changeBase(item, index) {
      if (this.updatePrices) {
        if (item.low_price != 0) {
          if (item.low != 0) {
            this.service.items[index].low_price =
              parseInt(item.price) + (item.price * item.low) / 100;
          } else {
            this.service.items[index].low_price = item.price;
          }
        }
        if (item.mid_price != 0) {
          if (item.mid != 0) {
            this.service.items[index].mid_price =
              parseInt(item.price) + (item.price * item.mid) / 100;
          } else {
            this.service.items[index].mid_price = item.price;
          }
        }
        if (item.high_price != 0) {
          if (item.high != 0) {
            this.service.items[index].high_price =
              parseInt(item.price) + (item.price * item.high) / 100;
          } else {
            this.service.items[index].high_price = item.price;
          }
        }
      }
      this.onChangePrice();
    },
    onChangePrice() {
      this.$forceUpdate();
      this.$root.$refs.create.$forceUpdate();
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
