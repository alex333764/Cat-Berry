<template>
    <div class="cart-page">
        <div class="cart-section">
            <p class="cart-title">Кошик</p>
            <div v-if="cart.length > 0" class="cart">

                <div class="cart-item" v-for="item in cart" :key="item.id ">
                    <img :src="'http://localhost/cafe/backend/images/' + item.image"/>

                    <div class="cart-info">
                        <h3>{{ item.name }}</h3>
                        <p>{{ item.size }}</p>
                    </div>

                    <div class="cart-controls">
                        <button @click="$emit('decrease', item)"> - </button>
                        <span>{{ item.quantity }}</span>
                        <button @click="$emit('increase', item)"> + </button>
                    </div>

                    <div class="cart-price">
                        {{ item.price * item.quantity }} грн
                    </div>

                    <button class="remove-btn" @click="$emit('remove', item)">×</button>
                </div>
            </div>
            <div v-else class="empty-cart">
                Тут поки що нічого немає...
            </div>
        </div>

        <div v-if="cart.length > 0" class="summary">
            <p>Підсумок</p>
            <div class="summary-price"> 
                {{ totalPrice }} грн <hr>
            </div>
            <h5>Бажаєте отримати ваше замовлення: </h5>
            <div class="order-type">
                <button class="type-btn" :class="{ active: orderType === 'to-go' }" @click="orderType = 'to-go'"> З собою </button>
                <button class="type-btn" :class="{ active: orderType === 'inside' }" @click="orderType = 'inside'"> У закладі </button>
            </div>
            <button class="checkout-btn" @click="checkout"> Оформити замовлення </button>
        </div>

        <div v-if=" recommendation && recommendation.recommendation && showRecommendation" class="recommendations-back">
            <div class="recommendations">
                <button class="recommendations-close" @click="closeRecommendation"> × </button>
                <div class="recommendations-text">
                    <h2> Разом з «{{ recommendation.base }}» часто замовляють «{{ recommendation.recommendation.name }}»</h2>
                    <p> Здається це смачне поєднання ☕️🍓 <br> Бажаєте спробувати? </p>
                    <button class="recommendations-btn" @click="addRecommendation"> Додати до кошика </button>
                </div>
                <img class="recommendations-image" :src="'http://localhost/cafe/backend/images/' + recommendation.recommendation.image"/>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            orderType: 'inside',
            recommendation: null,
            showRecommendation: true
        }
    },

    props: {
        cart: Array,
        user:Object
    },

    methods: {
        checkout() {
            fetch('http://localhost/cafe/backend/create_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    user:this.user?.login || null,
                    cart: this.cart,
                    total_price: this.totalPrice,
                    to_go: this.orderType === 'to-go'
                })
            })
            .then(response => response.json())

            .then(data => {
                alert(data.message)
                this.$emit('clear')
            })
        },

        loadRecommendation() {
            fetch('http://localhost/cafe/backend/get_recommendation.php', {
                method: 'POST',
                headers: {
                    'Content-Type':'application/json'
                },
                body: JSON.stringify({
                    cart: this.cart.map(i => i.name)
                })
            })
            .then(response => response.json())

            .then(data => {
                if (!data.recommendation) {
                    this.recommendation = null
                    return
                }

                const alreadyExists = this.cart.some(item => item.id === data.recommendation.id)
                if (alreadyExists) {
                    this.recommendation =null
                    return
                }
                this.recommendation = data
                this.showRecommendation = true
            })
        },

        addRecommendation() {
            const item = this.recommendation.recommendation
            const size = item.category_id == 1 || item.category_id == 2 ? 'small' : 'standard'
            this.$emit('add-to-cart', item, size, item.price)
            this.showRecommendation =false
        },

        closeRecommendation() {
            this.showRecommendation = false
        }
    },

    computed: {
        totalPrice() {
            let total = 0
            for (let item of this.cart) {
                total += item.price * item.quantity
            }
            return total
        }
    },

    watch: {
        cart: {
            handler() {
                if (!this.cart.length) {
                    this.recommendation = null
                    return
                }
                this.loadRecommendation()
            },
            deep: true,
            immediate: true
        }
    }
}
</script>
