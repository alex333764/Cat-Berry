<template>
    <div class="profile">
        <div class="sidebar">
            <div class="avatar"></div>
            <p class="user-name">{{ user.name }}</p>
            <p class="login"> @{{ user.login }} </p>
            <button class="logout" @click="$emit('logout')"> Вийти </button>
        </div>

        <div class="orders">
            <div class="receipt" v-for="order in orders" :key="order.id">
                <div class="thread"></div>
                <div class="hole"></div>
                <div class="receipt-products">
                    <div class="product" v-for="item in order.items" :key="item.id">
                        <img :src="'http://localhost/cafe/backend/images/'+item.image"/>
                        <div class="info">
                            <div class="name"> {{ item.name }}</div>
                            <div class="size"> {{ item.size }} </div>
                        </div>
                        <div class="price"> {{ item.quantity }} × {{ item.price }} грн </div>
                    </div>
                </div>
                <div class="line"></div>

                <div class="receipt-bottom">
                    <div> <p> Сума: <b>{{ order.total_price }} грн</b> </p> </div>
                    <div class="datetime">
                        <div> {{ formatDate(order.date) }} </div>
                        <div> {{ formatTime(order.date) }} </div>
                    </div>
                </div>
                <button class="repeat" @click="$emit('repeat-order', order.items)"> Замовити ще раз </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:{
            user:Object
        },

        data(){
            return{
                orders:[]
            }
        },

        methods:{
            formatDate(date){
                return new Date(date).toLocaleDateString('uk-UA')
            },

            formatTime(date){
                return new Date(date).toLocaleTimeString('uk-UA',{hour:'2-digit', minute:'2-digit'})
            }
        },

        mounted(){
            fetch('http://localhost/cafe/backend/get_orders.php', {credentials:'include'})
            .then(r=>r.json())
            .then(data=>{
                this.orders=data
            })
        }
    }
</script>