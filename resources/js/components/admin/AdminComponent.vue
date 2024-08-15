<template>
    <div>
        <div class="top-blue">
            <h2>BNB Bank</h2>
        </div>
        <div class="menu">
            <ul class="nav">
                <li class="nav-item">
                    <router-link :to="{name: 'admin.deposits'}" class="nav-link">Pending Deposits ({{ (totalDeposits) }})</router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{name: 'signin'}" class="nav-link">
                        <a @click.prevent="logout">Logout</a>
                    </router-link>
                </li>
            </ul>
        </div>
        <div class="container">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
export default {
    computed: {
        totalDeposits () {
            return this.$store.state.deposits.items.data.length
        }
    },

    methods: {
        logout () {
            this.$store.dispatch('logout')
                .then(() => this.$router.push({name: 'signin'}))
                .catch(errors => console.log(errors))
        },
    }
}
</script>

<style scoped>
    .top-blue {
        width: 100%;
        height: 20px;
        background-color: #bde0fe;
        color: white;
        padding: 20px 0;
        left: 0;
        text-align: center;
    }

    .menu {
        background-color: #bde0fe;
    }
</style>
