<template>
    <div class="details">
        <div class="details-card">
            <span class="close" @click="$emit('close')">×</span>
            <div class="details-body">
                <img :src="'http://localhost/cafe/backend/images/' + product.image" />

                <div>
                    <h2>{{ product.name }}</h2>
                    <p>{{ product.description }}</p>

                    <div v-if="isDrink" class="sizes">
                    <p> <b>Оберіть розмір:</b></p>
                        <div class="size-buttons">
                            <div class="size-btn" :class="{ active: selectedSize === 'small' }" @click="toggleSize('small')">
                                <img src="./images/Size_small.png" />
                            </div>

                            <div class="size-btn" :class="{ active: selectedSize === 'medium' }" @click="toggleSize('medium')">
                                <img src="./images/Size_medium.png" />
                            </div>

                            <div class="size-btn" :class="{ active: selectedSize === 'large' }" @click="toggleSize('large')">
                                <img src="./images/Size_large.png" />
                            </div>
                        </div>
                    </div>
                    <h3>{{ finalPrice }} грн</h3>
                    <button @click="add">Додати до кошика</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            selectedSize: 'small'
        }
    },

    props: {
        product: Object
    },

    computed: {
        isDrink() {
            return this.product.category_id == 1 || this.product.category_id == 2
        },

        finalPrice() {
            let base = Number(this.product.price)

            if (this.selectedSize === 'medium') return base + 10
            if (this.selectedSize === 'large') return base + 20

            return base
        }
    },

    methods: {
        toggleSize(size) {
            if (this.selectedSize === size) {
                this.selectedSize = 'small' 
            } else {
                this.selectedSize = size
            }
        },

        add() {
            const size = this.isDrink ? this.selectedSize : 'standard'
            this.$emit('add-to-cart', this.product, size, this.finalPrice)
            this.$emit('close')
        }
    }
}
</script>