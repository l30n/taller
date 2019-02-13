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
      <el-col :span="5" :offset="1">
        <label class="el-form-item__label">{{ item.name }}</label>
      </el-col>
      <el-col :span="3">
        <label class="el-form-item__label">${{ formatPrice(item.price) }}</label>
      </el-col>
      <el-col :span="2" style="text-align:center;" v-bind:class="{ priceSelected: price == 'low'}">
        <el-input class="percentage" maxlength="2" v-model="item.low"></el-input>
      </el-col>
      <el-col :span="3" style="text-align:center;" v-bind:class="{ priceSelected: price == 'low'}">
        <el-input class="price" v-model="item.low_price"></el-input>
      </el-col>
      <el-col :span="2" style="text-align:center;" v-bind:class="{ priceSelected: price == 'mid'}">
        <el-input class="percentage" maxlength="2" v-model="item.mid"></el-input>
      </el-col>
      <el-col :span="3" style="text-align:center;" v-bind:class="{ priceSelected: price == 'mid'}">
        <el-input class="price" v-model="item.mid_price"></el-input>
      </el-col>
      <el-col :span="2" style="text-align:center;" v-bind:class="{ priceSelected: price == 'high'}">
        <el-input class="percentage" maxlength="2" v-model="item.high"></el-input>
      </el-col>
      <el-col :span="3" style="text-align:center;" v-bind:class="{ priceSelected: price == 'high'}">
        <el-input class="price" v-model="item.high_price"></el-input>
      </el-col>
    </el-row>
    <el-row></el-row>
  </el-row>
</template>

<script>
export default {
  props: ["service", "price"],
  data() {
    return {
      percentages: []
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

<style lang="scss">
.priceSelected {
  background: rgb(242, 242, 242);
  label {
    line-height: 37px;
  }
}
</style>
