<template>
  <div class="con">
    <h2>Checkout</h2>

    <div class="inner">
      <form @submit.prevent="payment" @keydown="form.onKeydown($event)">
        <!-- Name -->
        <div class="mb-3">
          <label class="col-form-label">{{ $t("name") }} <span>*</span> </label>
          <div class="col-md-6">
            <input
              v-model="form.name"
              :class="{ 'is-invalid': form.errors.has('name') }"
              class="form-control"
              type="text"
              name="name"
            >
            <has-error :form="form" field="name" />
          </div>
        </div>

        <!-- Email -->
        <div class="mb-3">
          <label class="col-md-3 col-form-label">{{ $t("email") }} <span>*</span>
          </label>
          <div class="col-md-6">
            <input
              v-model="form.email"
              :class="{ 'is-invalid': form.errors.has('email') }"
              class="form-control"
              type="email"
              name="email"
            >
            <has-error :form="form" field="email" />
          </div>
        </div>

        <!-- Email -->
        <div class="mb-3">
          <label class="col-md-3 col-form-label">{{ $t("Phone Number") }} <span>*</span>
          </label>
          <div class="col-md-6">
            <input
              v-model="form.phone"
              :class="{ 'is-invalid': form.errors.has('phone') }"
              class="form-control"
              type="number"
              name="phone"
              required
            >
            <has-error :form="form" field="phone" />
          </div>
        </div>
        <div class="mb-3">
          <div class="col-md-6 d-flex">
            <!-- Submit Button -->
            <paystack
              v-if="user"
              :amount="(cart.length > 0) ? cart[0].price * 100 : 0"
              :email="user.email"
              :paystackkey="paystackkey"
              :reference="reference"
              :callback="callback"
              :close="close"
              :embed="false"
              style="margin-top: 10px"
              :disabled="loading || !form.phone || !form.email || !form.name"
              class="btn btn-primary"
            >
              Make Payment
            </paystack>
          </div>
        </div>
      </form>
      <!--
      <button @click="test">
        Click
      </button> -->
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import paystack from 'vue-paystack'
import Form from 'vform'
import axios from 'axios'
import Swal from 'sweetalert2/dist/sweetalert2.js'

export default {
  components: {
    paystack
  },
  middleware: 'auth',
  data: () => ({
    show_g_p: false,
    form: new Form({
      name: '',
      email: '',
      phone: ''
    }),
    loading: false,
    title: '',

    verifying: false,
    paystackkey: 'pk_live_4150b31d4aa1ac2069334e81077bd12f2bb7a181'
  }),
  computed: {
    ...mapGetters({
      user: 'auth/user',
      cart: 'cart/cart'
    }),
    reference () {
      let text = ''
      const possible =
        'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'

      for (let i = 0; i < 10; i++) {
        text += possible.charAt(Math.floor(Math.random() * possible.length))
      }

      return text
    }
  },
  created () {
    if (this.cart.length < 1) {
      this.$router.push({ name: 'welcome' })
    }
    // Fill the form with user data.
    this.form.keys().forEach((key) => {
      this.form[key] = this.user[key]
    })
  },

  methods: {
    successMsg () {
      Swal.fire({
        title: '<strong>Payment Successful!</strong>',
        icon: 'success',
        html: 'Thanks for buying our novel ❤️',
        showCloseButton: true,
        showCancelButton: false,
        focusConfirm: false,
        confirmButtonColor: '#9f5d01',
        closeOnConfirm: false,
        confirmButtonText: '<i class="fas fa-download"></i> Download!',
        confirmButtonAriaLabel: 'Download!',
        showLoaderOnConfirm: true,
        focusCancel: true,
        preConfirm: () => {
          return new Promise((resolve) => {
            axios
              .get('/api/novel/download', { responseType: 'blob' })
              .then((response) => {
                const url = window.URL.createObjectURL(
                  new Blob([response.data])
                )
                const link = document.createElement('a')
                link.href = url
                link.setAttribute('download', this.title + '.pdf')
                document.body.appendChild(link)
                link.click()
                link.remove()
                resolve(true)
                this.loading = false
              })
          })
        }
      })
    },
    payment () {},
    verifyPay (response) {
      this.loading = true
      const data = { ...this.form }
      this.title = this.cart[0].title
      data.amount = this.cart[0].price
      data.reference = response.reference
      data.cart = this.cart[0]

      this.loadingV = true

      axios
        .post('/api/verify-payment', data)
        .then(() => {
          this.successMsg()
          this.$store.dispatch('cart/deleteCart')
          this.$router.push({ name: 'dashboard.orders' })
          this.loadingV = false
        })
        .catch(() => {
          console.log('Payment Failed')
        })
    },
    callback: function (response) {
      if (response.status === 'success') {
        this.verifyPay(response)
      }
    },
    close: function () {}
  }
}
</script>

<style lang="scss" scoped>
label span {
  color: orangered;
}
.con {
  margin: 10px;
  padding: 10px;
  .inner {
    .checkout-card {
      box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
      background: white;
      padding: 20px;
      margin: 10px;
      border-radius: 3px;
      max-width: 300px;

      h4 {
        font-size: 1.2rem;
      }
    }
  }
}

@media (max-width: 640px) {
  .inner {
    flex-direction: column;
    .checkout-card {
      max-width: 100%;
    }
  }
}
</style>>
