<template>
    <div>
        <div class="container">
            <h5 class="title">CHECK DETAILS</h5>

            <form class="form table table-size">
                <div class="form-group form-imput">
                    <label class="label-text">Customer</label>
                    <input type="text" v-model="deposit.customer" class="form-control">
                </div>
                <div class="form-group form-imput">
                    <label class="label-text">Customer Email</label>
                    <input type="text" v-model="deposit.email" class="form-control">
                </div>
                <div class="form-group form-imput">
                    <label class="label-text">Account</label>
                    <input type="text" v-model="deposit.account" class="form-control">
                </div>
                <div class="form-group form-imput">
                    <label class="label-text">Reported Amount</label>
                    <input type="text" v-model="deposit.amount" class="form-control">
                </div>
                <div class="form-group form-imput">
                    <img :src="`/storage/deposits/${deposit.image}`" :alt="deposit.description">
                </div>
                <div>
                    <a href="#" class="btn btn-primary btn-reject" @click.prevent="reject(deposit)">REJECT</a>
                    <a href="#" class="btn btn-primary" @click.prevent="approve(deposit)">ACCEPT</a>
                </div>
            </form>

        </div>
    </div>
</template>

<script>
import deposits from "../../../../vuex/modules/deposits/deposits";

export default {
    computed: {
        deposits() {
            return deposits
        }
    },
    props: {
        id: {
            required: true
        }
    },

    data () {
        return {
            deposit: {
                customer: '',
                email: '',
                account: '',
                amount: '',
                image: '',
            }
        }
    },

    created() {
        this.loadDepositById();
    },

    methods: {
        loadDepositById() {
            this.$store.dispatch('loadDepositById', this.id)
                .then(response => {
                    this.deposit = response.data
                })
                .catch(errors => {
                    this.$snotify.error("Something went wrong!", "Ops...");
                    this.$router.push({name: 'admin.deposits'});
                })
        },

        approve(deposit) {
            this.$store.dispatch('approveDeposit', deposit.id)
                .then(() => {
                    this.$snotify.success("Check Deposit Accepted!");
                    this.$router.push({name: 'admin.deposits'})
                })
                .catch(error => {
                    console.log(error)
                    this.$snotify.error("Something went wrong!", "Ops...");
                    this.$router.push({name: 'admin.deposits'});
                })
        },

        reject(deposit) {
            this.$store.dispatch('rejectDeposit', deposit.id)
                .then(() => {
                    this.$snotify.success("Check Deposit Rejected!");
                    this.$router.push({name: 'admin.deposits'})
                })
                .catch(error => {
                    console.log(error)
                    this.$snotify.error("Something went wrong!", "Ops...");
                    this.$router.push({name: 'admin.deposits'});
                })
        }
    }
}
</script>

<style scoped>
    .table-size {
        width: 50%;
    }
    .form-imput {
        padding-bottom: 10px;
    }
    .btn-reject {
        background-color: white;
        color: #0b5ed7;
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
</style>
