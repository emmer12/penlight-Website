<template>
  <div class="row">
    <product-card v-for="(product,index) in products" :key="index" :product="product" />
  </div>
</template>

<script>
import axios from 'axios'

export default {

  components: {

  },
  data () {
    return {
      products: []
    }
  },
  created () {
    this.getProducts()
  },

  methods: {
    async getProducts () {
      const { data: { products } } = await axios.get('/api/products')

      this.products = products
    }
  },
  // async asyncData () {
  //   const { data: products } = await axios.get('/api/products')

  //   return {
  //     products
  //   }
  // },

  metaInfo () {
    return { title: this.$t('home') }
  }
}
</script>

<style >
input{

}
</style>
