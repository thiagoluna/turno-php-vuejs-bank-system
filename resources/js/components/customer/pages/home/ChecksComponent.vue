<template>
    <div>
        <div class="container">
            <h5 class="title">CHECKS</h5>
            <router-link :to="{name: 'checks.add'}" class="btn btn-info btn-add">+</router-link>
            <table class="table table-light">
                <tbody>
                <tr v-for="(transaction, index) in transactions.data" :key="index">
                    <td class="transaction-text-bold">{{ transaction.description }}</td>
                    <td class="transaction-text">{{ transaction.date }}</td>
                    <td class="transaction-text">${{ transaction.amount }}</td>
                    <td v-if="transaction.status == 'pending'" class="yellow">{{ transaction.status }}</td>
                    <td v-if="transaction.status == 'approved'" class="blue">{{ transaction.status }}</td>
                    <td v-if="transaction.status == 'rejected'" class="red">{{ transaction.status }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    created () {
        this.loadTransactions()
    },
    data () {
        return {
            type: 'deposit',
            bank_account_id: this.$store.state.auth.me.bank_account
        }
    },
    computed: {
        transactions () {
            return this.$store.state.transactions.items
        }
    },
    methods: {
        loadTransactions() {
            this.$store.dispatch('loadTransactions', {type: this.type, bank_account_id: this.bank_account_id})
        },
    }
}
</script>

<style scoped>
    .yellow {
        color: #ffda6a;
    }
    .blue {
        color: #0b5ed7;
    }
    .red {
        color: red;
    }
    .btn-add {
        color: white;
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
