<template>
    <header class="navbar">
        <div class="navbar-left">
            <img class="logo" src="./images/cafe_logo_f1.png" alt="logo" @click="$emit('changePage', 'home')"/>
            <button @click="$emit('changePage', 'menu')"> Меню </button>
        </div>
        <div class="navbar-right">
            <div class="nav-cart" @click="$emit('changePage', 'cart')">
                <img src="./images/cart.png" />
                <span v-if="cartCount > 0" class="cart-count"> {{ cartCount }} </span>
            </div>
            <img class="pp" src="./images/Personal_page_g.png" alt="pp" @click="$emit('changePage', user ? 'profile' : 'login')"/>
        </div>
    </header>
</template>

<script>
export default {
    props: {
        cartCount: Number,
        user:Object
    }
}
</script>