import * as types from '../mutation-types'

// state
export const state = {
  cart: JSON.parse(localStorage.getItem('cart')) || []
}

// getters
export const getters = {
  cart: state => state.cart
}

// mutations
export const mutations = {
  [types.SAVE_CART] (state, cart) {
    if (state.cart.length) return
    state.cart.push(cart)

    localStorage.setItem('cart', JSON.stringify(state.cart))
  },
  [types.REMOVE_CARTS] (state) {
    state.cart = []
    localStorage.removeItem('cart')
  }
}

// actions
export const actions = {
  saveCart ({ commit }, cart) {
    commit(types.SAVE_CART, cart)
  },

  deleteCart ({ commit }) {
    commit(types.REMOVE_CARTS)
  }
}
