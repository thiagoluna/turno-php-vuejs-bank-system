<template>
    <div>
        <h5 class="title">CHECKS CONTROL</h5>

        <table class="table table-white">
            <tbody>
            <tr v-for="(deposit, index) in deposits.data" :key="index">
                <td class="transaction-text-bold">{{ deposit.customer}}</td>
                <td class="transaction-text">{{ deposit.date }}</td>
                <td class="transaction-text-bold">{{ deposit.amount }}</td>
                <td>
                    <router-link :to="{name: 'admin.deposits.edit', params: {id: deposit.id} }" class="btn btn-primary">
                        Edit
                    </router-link>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>

export default {
    props: {
        id: {
            require: true
        }
    },

    created () {
        this.loadDeposits()
    },

    computed: {
        deposits () {
            return this.$store.state.deposits.items
        }
    },

    methods: {
        loadDeposits() {
            this.$store.dispatch('loadDeposits')
        },
    }
}
</script>

<style scoped>
    .red {
        color: red;
    }
    .transaction-text-bold {
        font-size: 14px;
        color: #87cefa;
        font-weight: bold;
    }
    .transaction-text {
        font-size: 14px;
        color: #87cefa;
    }
    .title {
        margin: 20px 0 20px 0;
        color: #87cefa;
        font-weight: bold;
    }
</style>
