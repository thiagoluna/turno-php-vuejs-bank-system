<template>
    <div>
        <div class="container">
            <h5 class="title">PURCHASE</h5>
            <label class="label-text">CURRENT BALANCE</label>
            <div class="balance">${{balance}}</div>

            <form class="form" @submit.prevent="submitForm">
                <label class="label-text">Amount USD</label>
                <div v-if="errors.amount" class="error-text">{{ errors.amount[0] }}</div>
                <div class="form-group form-imput">
                    <input type="text" v-model="amount" class="form-control" placeholder="9999.99" required>
                </div>
                <label class="label-text">Date</label>
                <div v-if="errors.date" class="error-text">{{ errors.date[0] }}</div>
                <div class="form-group form-imput">
                    <input type="text" v-model="date" v-mask="'##/##/####'" class="form-control" placeholder="mm/dd/yyyy" required>
                </div>
                <label class="label-text">Description</label>
                <div v-if="errors.description" class="error-text">{{ errors.description[0] }}</div>
                <div class="form-group form-imput">
                    <input type="text" v-model="description" class="form-control" placeholder="Description" required>
                </div>
                <div class="form-group form-imput">
                    <button class="btn btn-primary">Add Purchase</button>
                </div>
            </form>

        </div>
    </div>
</template>

<script>
export default {
    data () {
        return {
            description: '',
            amount: '',
            date: '',
            errors: {},
            balance: this.$store.state.transactions.items.balance
        }
    },
    methods: {
        submitForm () {
            this.$store.dispatch(
                'addPurchase',
                {
                    'description': this.description,
                    'amount': this.amount,
                    'date': this.date,
                    'bank_account_id': this.$store.state.auth.me.bank_account
                }
            )
                .then(() => {
                    this.$snotify.success("Expense Added!");
                    this.$router.push({name: 'expenses'});
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
    .form-imput {
        padding-bottom: 10px;
    }
    .error-text {
        color: red;
    }
    .title {
        margin: 20px 0 20px 0;
        color: #87cefa;
        font-weight: bold;
    }
    .label-text {
        font-size: 14px;
        color: #87cefa;
    }
    .balance {
        color: #87cefa;
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 30px;
    }
</style>
