<template>
    <div>
        <div class="container">
            <h5 class="title">CHECK DEPOSIT</h5>
            <label class="label-text">CURRENT BALANCE</label>
            <div class="balance">${{balance}}</div>

            <form class="form" @submit.prevent="submitForm">
                <div class="form-group form-imput">
                    <label class="label-text">Amount USD</label>
                    <div v-if="errors.amount" class="error-text">{{ errors.amount[0] }}</div>
                    <input type="text" v-model="amount" class="form-control" placeholder="9999.99" required>
                </div>
                <div class="form-group form-imput">
                    <label class="label-text">Description</label>
                    <div v-if="errors.description" class="error-text">{{ errors.description[0] }}</div>
                    <input type="text" v-model="description" class="form-control" placeholder="Enter a description" required>
                </div>
                <div class="form-group form-imput">
                    <div v-if="errors.image" class="error-text">{{ errors.image[0] }}</div>
                    <input type="file" @change="onFileChange">
                </div>
                <div class="form-group form-imput" v-if="imagePreview">
                    <img :src="imagePreview">
                </div>
                <div class="form-group form-imput">
                    <button class="btn btn-primary">DEPOSIT CHECK</button>
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
            image: '',
            upload: null,
            imagePreview: null,
            errors: {},
            bank_account_id: this.$store.state.auth.me.bank_account,
            balance: this.$store.state.transactions.items.balance
        }
    },

    methods: {
        submitForm () {
            const formData = new FormData();
            formData.append('description', this.description);
            formData.append('amount', this.amount);
            formData.append('image', this.upload);
            formData.append('bank_account_id', this.$store.state.auth.me.bank_account);

            this.$store.dispatch('addDeposit', formData)
                .then(() => {
                    this.$snotify.success("Check Deposit Added!");
                    this.$router.push({name: 'checks'});
                })
                .catch(error => {
                    this.$snotify.error("Something went Wrong!", "Ops...");
                    this.errors = error.response.data.errors;
                })
        },

        onFileChange (e) {
            let files = e.target.files || e.dataTransfer.files
            if (!files.length)
                return

            this.upload = files[0]

            this.previewImage(files[0])
        },

        previewImage (file) {
            let reader = new FileReader();
            reader.onload = (e) => {
                this.imagePreview = e.target.result;
            }
            reader.readAsDataURL(file)
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
