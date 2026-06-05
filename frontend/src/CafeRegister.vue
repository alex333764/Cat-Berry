<template>
    <div class="auth">
        <div class="auth-card">
            <h1>Реєстрація</h1>
            <input v-model="name" placeholder="Ім’я" @input="errors.name = ''"/>
            <p class="error" v-if="errors.name">
                {{ errors.name }}
            </p>
            <input v-model="login" placeholder="Логін" @input="errors.login = ''"/>
            <p class="error" v-if="errors.login">
                {{ errors.login }}
            </p>
            <input type="password" v-model="password" placeholder="Пароль" @input="errors.password = ''"/>
            <p class="error" v-if="errors.password">
                {{ errors.password }}
            </p>
            <input type="password" v-model="repeatPassword" placeholder="Повторіть пароль" @input="errors.repeatPassword = ''"/>
            <p class="error" v-if="errors.repeatPassword">
                {{ errors.repeatPassword }}
            </p>
            <button @click="register"> Зареєструватись </button>
            <p> Вже маєте акаунт? <span @click="$emit('login')"> <u> Увійти </u> </span> </p>
        </div>
    </div>
</template>

<script>
export default {

    data() {
        return {
            name: '',
            login: '',
            password: '',
            repeatPassword: '',
            errors: {
                name: '',
                login: '',
                password: '',
                repeatPassword: ''
            }
        }
    },

    methods: {
        register() {
            this.errors = {
                name: '',
                login: '',
                password: '',
                repeatPassword: ''
            }
            let hasError = false

            if (!this.name.trim()) {
                this.errors.name = 'Введіть ім’я'
                hasError = true
            }

            if (!this.login.trim()) {
                this.errors.login = 'Введіть логін'
                hasError = true
            }

            if (!this.password) {
                this.errors.password = 'Введіть пароль'
                hasError = true
            }

            if (!this.repeatPassword) {
                this.errors.repeatPassword = 'Повторіть пароль'
                hasError = true
            }

            if ( this.password && this.repeatPassword && this.password !== this.repeatPassword) {
                this.errors.repeatPassword = 'Паролі не співпадають'
                hasError = true
            }

            if (hasError)
            return

            fetch('http://localhost/cafe/backend/register.php', {
                method:'POST',
                credentials:
                    'include',
                headers:{
                    'Content-Type':'application/json'
                },
                body:JSON.stringify({
                    name:this.name,
                    login:this.login,
                    password:this.password
                })
            })
            .then(r=>r.json())
            .then(data=>{
                if (data.field) {
                    this.errors[data.field] = data.message
                    return
                }
                this.$emit('register-success',data.user)
            })
        }
    }
}
</script>