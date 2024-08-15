<template>
    <div>
        <div class="financial">
            <div class="box">Balance: ${{ transactions.balance }}</div>
            <div class="box">Incomes: ${{ transactions.incomes }}</div>
            <div class="box">Expenses: ${{ transactions.expenses }}</div>
        </div>
        <div class="container">
            <h3 class="transaction-text">TRANSACTIONS</h3>
            <table class="table table-light">
                <tbody>
                <tr v-for="(transaction, index) in transactions.data" :key="index">
                    <td class="transaction-text">{{ transaction.description }}</td>
                    <td class="transaction-text">{{ transaction.date }}</td>
                    <td v-if="transaction.type == 'purchase'" class="red">-${{ transaction.amount }}</td>
                    <td v-if="transaction.type != 'purchase'" class="transaction-text">${{ transaction.amount }}</td>
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
            type: '',
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
    .red {
        color: red;
    }

    .financial {
        display: flex;
        justify-content: space-between;
        background-color: #f0f8ff;
        color: #bde0fe;
        margin-bottom: 20px;
    }

    .box {
        width: 30%; /* Ajuste a largura conforme necessário */
        padding: 10px;
        //background-color: #f0f0f0;
        text-align: center;
        color: #0000ff;
    }

    .transaction-text {
        font-size: 14px;
        color: #87cefa;
        font-weight: bold;
    }
</style>
