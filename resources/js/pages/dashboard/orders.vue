<template>
  <div>
    <card :title="$t('Your orders')">
      <div v-if="orders.length" class="table-responsive">
        <table class="table table-striped">
          <thead class="thead-dark">
            <tr>
              <th scope="col">
                #
              </th>
              <th scope="col">
                Preview
              </th>
              <th scope="col">
                Name
              </th>
              <th scope="col">
                Status
              </th>
              <th scope="col">
                Transaction ID
              </th>

              <th scope="col">
                Action
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(order,index) in orders" :key="index">
              <th scope="row">
                {{ index + 1 }}
              </th>
              <td>
                <div style="height:70px;width:50px">
                  <img :src="order.product.preview" width="100%">
                </div>
              </td>
              <td style="text-align: center; vertical-align: middle;min-width:130px">
                {{ order.product.title }}
              </td>
              <td style="text-align: center; vertical-align: middle;">
                <div class="badge" :class=" order.payment_status=='paid' ? 'badge-success' :'badge-warning'">
                  {{ order.payment_status }}
                </div>
              </td>
              <td style="text-align: center; vertical-align: middle;">
                {{ order.transaction_id }}
              </td>
              <td style="text-align: center; vertical-align: middle;">
                <div v-if="user.isAdmin" class="btn btn-primary btn-sm" style="cursor: pointer;white-space:nowrap" @click="showDetails(order.user)">
                  View User
                </div>
                <div v-else style="cursor: pointer;white-space:nowrap" @click="download(order.product.title)">
                  <i class="fa fa-download" />
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else>
        <div class="alert alert-info">
          <i class="fa fa-user" />
          No Orders Yet
        </div>
      </div>
    </card>
  </div>
</template>

<script>
import axios from 'axios'
import { mapGetters } from 'vuex'
import Swal from 'sweetalert2/dist/sweetalert2.js'

export default {
  middleware: 'auth',

  data () {
    return {
      orders: [],
      fetching: false
    }
  },

  computed: mapGetters({
    user: 'auth/user'
  }),
  created () {
    this.getOreders()
  },

  methods: {

    showDetails (user) {
      Swal.fire(
        {
          title: 'User Details',
          html: `<div style="text-align:start">
          <b>Name:</b> ${user.name} <br>
             <b>Email Address:</b>${user.email}</div>
        `
        }

      )
    },

    download (title) {
      axios.get('/api/novel/download', { responseType: 'blob' }).then((response) => {
        console.log(response)
        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement('a')
        link.href = url
        link.setAttribute('download', title + '.pdf')
        document.body.appendChild(link)
        link.click()
        link.remove()

        const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
          }
        })

        Toast.fire({
          icon: 'success',
          title: 'Downloaded successfully'
        })
      })
    },
    async getOreders () {
      try {
        this.fetching = true
        const { data: { orders } } = await axios.get('/api/orders')
        this.orders = orders
        this.fetching = false
      } catch (error) {
        this.fetching = false
      }
    }
  }
}
</script>

<style>
.settings-card .card-body {
  padding: 0;
}

.badge-success{
  background: #28a745;
}

.badge-warning{
  background: orange;
}
</style>
