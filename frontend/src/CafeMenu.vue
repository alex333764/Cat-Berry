<template>
    <div class="menu-page">
        <div class="menu">
            <p class="h-1" :class="isActive([1,2]) ? 'active' : ''" @click="selectedCategories = [1,2]">Напої</p>
            <p class="h-2" :class="isActive([1,2]) ? 'active' : ''" @click="selectedCategories = [1,2]">Гарячі напої</p>
            <p class="h-3" :class="isActive([1]) ? 'active' : ''" @click="selectedCategories = [1]">Кава</p>
            <p class="h-3" :class="isActive([2]) ? 'active' : ''" @click="selectedCategories = [2]">Чай</p>
            <p class="h-1" :class="isActive([3]) ? 'active' : ''" @click="selectedCategories = [3]">Десерти</p>
        </div>
        <div class="products">
            <div class="card" v-for="product in filteredProducts" :key = product.id @click="selectedProduct = product">
                <img :src="'http://localhost/cafe/backend/images/' + product.image"/>
                <h1>{{ product.name }}</h1>
                <p>{{ product.price }} грн</p>
                <button @click.stop="add(product)"> Додати до кошика </button>
            </div>
            <product-details v-if = "selectedProduct" :product = "selectedProduct" @close = "selectedProduct = null" @add-to-cart="(product, size, price) => $emit('add-to-cart', product, size, price)"></product-details>
        </div>
    </div>
</template>

<script>
import ProductDetails from './ProductDetails.vue';

export default {
    data() {
        return {
            products: [],
            selectedProduct: null,
            selectedCategories: [1,2]
        }
    },

    methods: {
        isActive(categories) {
            return JSON.stringify(this.selectedCategories) === JSON.stringify(categories)
        },

        add(product) {
            const size = product.category_id == 1 || product.category_id == 2 ? 'small' : 'standard'
            this.$emit('add-to-cart', product, size, product.price)
        }
    },

    computed: {
        filteredProducts() {
            if (!this.selectedCategories) {
                return this.products
            }

            return this.products.filter (
                product => this.selectedCategories.includes(Number(product.category_id))
            )
        }

    },

    components: {
        'product-details': ProductDetails
    },

    mounted() {
        fetch('http://localhost/cafe/backend/get_products.php')
            .then((response) => {
                return response.json();
            })
            .then((data) => {
                this.products = data;
            });
        }
}
</script>