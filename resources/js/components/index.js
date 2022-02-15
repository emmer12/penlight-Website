import Vue from 'vue'
import Card from './Card.vue'
import ProductCard from './ProductCard.vue'
import Child from './Child.vue'
import Button from './Button.vue'
import Checkbox from './Checkbox.vue'
import { HasError, AlertError, AlertSuccess } from 'vform/components/bootstrap5'

// Components that are registered globaly.
[
  Card,
  Child,
  Button,
  Checkbox,
  HasError,
  AlertError,
  AlertSuccess,
  ProductCard

].forEach(Component => {
  Vue.component(Component.name, Component)
})
