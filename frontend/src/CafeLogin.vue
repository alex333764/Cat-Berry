<template>
    <div class="auth">
        <div class="auth-card">
            <h1>Вхід</h1>
            <input v-model="login" placeholder="Логін" @input="errors.login = ''"/>
            <p class="error" v-if="errors.login">
                {{ errors.login }}
            </p>
            <input v-model="password" type="password" placeholder="Пароль" @input="errors.password = ''"/>
            <p class="error" v-if="errors.password">
                {{ errors.password }}
            </p>
            <button @click="loginUser"> Увійти </button>
            <p> Ще не зареєстровані? <span @click="$emit('register')"> <u> Зареєструватись</u> </span> </p>
        </div>
    </div>
</template>

<script>
export default {

    data() {
        return {
            login: '',
            password: '',
            errors: {
                login: '',
                password: ''
            }
        }
    },

    methods: {
        loginUser() {
            this.errors = {
                login:'',
                password:''
            }
            let error = false

            if(!this.login.trim()){
                this.errors.login = 'Введіть логін'
                error = true
            }

            if(!this.password){
                this.errors.password = 'Введіть пароль'
                error = true
            }

            if(error)
                return

            fetch('http://localhost/cafe/backend/login.php', {
                method:'POST',
                credentials:
                    'include',
                headers:{
                    'Content-Type':'application/json'
                },
                body:JSON.stringify ({
                    login:this.login,
                    password:this.password
                })
            })
            .then((response) => {
                return response.json();
            })
            .then(data => {
                if(data.field) {
                    this.errors[data.field] = data.message
                    return
                }
                this.$emit('login-success', data.user)
            })
        }
    }
}
</script>