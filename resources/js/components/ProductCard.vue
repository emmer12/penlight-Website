<template>
  <div class="col-c card card-product">
    <div class="">
      <router-link :to="{name:'novel-show',params:{slug:product.slug}}" class="col-c">
        <div class="preview">
          <img :src="product.preview" width="100%">
        </div>
      </router-link>
      <div class="details">
        <div class="title">
          <h4>{{ product.title }}</h4>

          <a v-if="product.price == 0" :href="`/novels/${product.file_path}`" :download="`/novels/${product.file_path}`" class="btn btn-primary btn-sm">
            Download
          </a>
          <button v-else class="btn btn-primary btn-sm" @click="addToCart">
            Buy Now &#8358; {{ product.price }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProductCard',

  props: {
    product: { type: Object, default: null }
  },

  methods: {
    async addToCart () {
      const cart = {
        id: this.product.id,
        title: this.product.title,
        price: this.product.price
      }
      await this.$store.dispatch('cart/saveCart', cart)

      this.$router.push({ name: 'novel-checkout' })
    }

  }
}
</script>

<style lang="scss" scoped>
  .card-product{
     padding:10px;
     border-radius: 10px;
     margin:10px;

     .preview{
       height: 260px;
       position: relative;
     }
     .title{
       padding:10px;
       h4{
         font-size: 12px;
       }
     }
     img{
     border-radius: 10px;
     height: 100%;
     object-fit: cover;
     }
  }
  .col-c{
    width: 200px;
    cursor: pointer;
  }
 @media (max-width:640px) {
     .card-product{
       margin:5px;
         .preview{
       height: 200px;
     }
     }
       .col-c{
         width: calc(100% / 2 - 10px);
   }
 }
</style>
