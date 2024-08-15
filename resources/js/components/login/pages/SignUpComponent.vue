<template>
    <div class="wrapper">
        <div class="header">
            <h1>BNB Bank</h1>
        </div>
        <div class="container">
            <form @submit.prevent="onSubmit">
                <div v-if="errors.name" class="error-text">{{ errors.name[0] }}</div>
                <input type="text" v-model="formData.name" placeholder="username" required />

                <div v-if="errors.email" class="error-text">{{ errors.email[0] }}</div>
                <input type="email" v-model="formData.email" placeholder="email" required />

                <div v-if="errors.password" class="error-text">{{ errors.password[0] }}</div>
                <input type="password" v-model="formData.password" placeholder="password" required />

                <button type="submit">SIGN UP</button>
            </form>
            <div class="divider">__________</div>
            <router-link to="/signin" class="login-link">Already have an account?</router-link>
        </div>
    </div>
</template>

<script>
export default {
    data () {
        return {
            formData: {
                name: '',
                email: '',
                password: '',
            },
            errors: {},
        }
    },

    methods: {
        onSubmit() {
            const formData = new FormData();
            formData.append('name', this.name);
            formData.append('email', this.email);
            formData.append('password', this.password);

            this.$store.dispatch('signup', this.formData)
                .then(() => {
                    this.$snotify.success("SignUp with success!");
                    this.$router.push({name: 'signin'});
                })
                .catch(error => {
                    this.$snotify.error("Something went Wrong!", "Ops...");
                    this.errors = error.response.data.errors;
                })
        }
    }
}
</script>

<style scoped>
body {
    margin: 0;
    padding: 0;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: Arial, sans-serif;
    background-color: #f8f8f8;
}

.wrapper {
    width: 100%;
    text-align: center;
}

.header {
    width: 100%;
    background-color: #2196F3;
    color: white;
    padding: 20px 0;
    position: absolute;
    top: 0;
    left: 0;
}

.container {
    width: 360px;
    padding: 20px;
    text-align: center;
    margin-top: 100px;
}

input[type="text"],
input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    box-sizing: border-box;
    border: 2px solid #2196F3;
    border-radius: 25px;
    outline: none;
    font-size: 14px;
    color: #777;
}

input[type="text"]::placeholder,
input[type="email"]::placeholder,
input[type="password"]::placeholder {
    color: #a0d3ff;
}

button {
    width: 100%;
    background-color: #2196F3;
    color: white;
    padding: 14px 20px;
    margin: 10px 0;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    background-color: #1976D2;
}

.login-link {
    margin-top: 20px;
    display: block;
    color: #a0d3ff;
    text-decoration: none;
    font-size: 14px;
}

.divider {
    margin: 20px 0;
    color: #e0e0e0;
    font-size: 12px;
}

.error-text {
    color: red;
}
</style>
