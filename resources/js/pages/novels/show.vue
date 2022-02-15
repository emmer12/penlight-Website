<template>
  <div class="row">
    <div v-if="product" class="con">
      <div class="preview">
        <img :src="product.preview">
      </div>
      <div class="details">
        <div>
          <img src="" alt="">
          <div class="author">
            <h2>{{ product.title }}</h2>
            <h6>By <span> {{ product.user.name }} </span></h6>
          </div>

          <div>
            <p>
              {{ product.description }}
            </p>
          </div>

          <a v-if="product.price == 0" :href="`/novels/${product.file_path}`" :download="`/novels/${product.file_path}`" class="btn btn-primary btn-round">
            Download
          </a>
          <button v-else class="btn btn-primary btn-round" @click="addToCart">
            Buy Now &#8358; {{ product.price }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  data () {
    return {
      product: null
    }
  },
  created () {
    this.getProduct()
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
    },

    async getProduct () {
      const { data: { product } } = await axios.get('/api/products/' + this.$route.params.slug)

      this.product = product
    }
  },

  metaInfo () {
    return { title: this.$t('home') }
  }
}
</script>

<style>
button {
  /* border-radius: 20px !important; */
}
</style>

<style lang="scss" scoped>
.con {
  max-width: 600px;
  margin: auto;
  top: 50px;
  position: relative;
  padding: 10px;
  border-radius: 20px;
  box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
  background: #fff;
  display: flex;

  .preview {
    width: 40%;
    flex-shrink: 0;
    img {
      width: 100%;
      border-radius: 10px;
    }
  }

  .details {
    padding: 15px;

    .author {
      margin: 10px 0px;

      h2 {
        font-size: 1.5rem;
      }

      span {
        color: #555;
      }
    }
    p {
      color: #878787;
    }
  }
}

@media (max-width: 640px) {
  .con {
    flex-direction: column;
    .preview {
      width: 100%;
    }
  }
}
</style>
