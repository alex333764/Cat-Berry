<template>
  <cafe-navbar @changePage="(p) => currentPage = p" :cartCount="cart.length" :user="user"></cafe-navbar>
  <cafe-home v-if="currentPage === 'home'"></cafe-home>
  <cafe-menu v-if="currentPage === 'menu'" @add-to-cart="addToCart"></cafe-menu>
  <cafe-cart v-if="currentPage === 'cart'" :cart="cart" :user="user" @increase="increaseItem" @decrease="decreaseItem" 
    @remove="removeItem" @clear="cart = []" @add-to-cart="addToCart"></cafe-cart>
  <cafe-login v-if="currentPage === 'login'" @register="currentPage='register'" @login-success="toProfile"></cafe-login>
  <cafe-register v-if="currentPage==='register'" @login="currentPage='login'" @register-success="toProfile"></cafe-register>
  <cafe-profile v-if="currentPage==='profile'" :user="user" @logout="logout" @repeat-order="repeatOrder"></cafe-profile>
</template>

<script>
import CafeNavbar from './CafeNavbar.vue';
import CafeHome from './CafeHome.vue';
import CafeMenu from './CafeMenu.vue';
import CafeCart from './CafeCart.vue';
import CafeLogin from './CafeLogin.vue';
import CafeRegister from './CafeRegister.vue';
import CafeProfile from './CafeProfile.vue';

export default {
  data() {
    return {
      currentPage: "home",
      cart: [],
      user: null
    }
  },

  components: {
    'cafe-navbar': CafeNavbar,
    'cafe-home': CafeHome,
    'cafe-menu': CafeMenu,
    'cafe-cart': CafeCart,
    'cafe-login': CafeLogin,
    'cafe-register': CafeRegister,
    'cafe-profile': CafeProfile
  },

  methods: {
    addToCart(product, size, price) {
      const existing = this.cart.find(item => item.id === product.id && item.size === size)

      if (existing) {
        existing.quantity++
      } else {
        this.cart.push({
          id: product.id,
          name: product.name,
          image: product.image,
          price: price,
          size: size,
          quantity: 1
        })
      }
    },

    increaseItem(item) {
      item.quantity++
    },

    decreaseItem(item) {
      if (item.quantity > 1) {
        item.quantity--
      }
    },

    removeItem(item) {
      const index = this.cart.indexOf(item)
      if (index !== -1) {
        this.cart.splice(index, 1)
      }
    },

    toProfile(user){
      this.user = user
      this.currentPage = 'profile'
    },

    logout(){
      fetch('http://localhost/cafe/backend/logout.php', {credentials:'include'})
      .then((response) => {
        return response.json();
      })
      .then(() => {
        this.user = null
        this.currentPage = 'home'
      })
    },

    repeatOrder(items){
      this.cart = []

      for(let item of items){
        this.cart.push({
          id: item.id,
          name: item.name,
          image: item.image,
          price: item.price,
          size: item.size,
          quantity: item.quantity
        })
      }
      this.currentPage = 'cart'
    }
  },

  mounted() {
    fetch('http://localhost/cafe/backend/check_session.php', {credentials:'include'})
    .then((response) => {
      return response.json();
    })
    .then(data => {
      if(data.authorized){
        this.user = data.user
      }
    })
  }
}
</script>