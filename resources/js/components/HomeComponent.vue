<template>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h2 style="text-align:center;color:#1d68a7">Last block


                    </h2>
                    <h5 style="text-align:center" v-if="!block">
                        <div class="spinner-border text-secondary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        Waiting
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-striped" v-if="block">
                            <thead class="thead-light">
                            <tr>
                                <th>Hash</th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{block.hash}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
                <hr>
                <div class="card-body">
                    <h2 style="text-align:center;color:#1d68a7">Latest transactions</h2>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="thead-light">
                            <tr>
                                <th>Transaction id</th>
                                <th>Inputs</th>
                                <th>Outputs</th>
                                <th>Size</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="transaction in transactions">
                                <td>{{transaction.transaction_id}}</td>
                                <td>{{transaction.inputs}}</td>
                                <td>{{transaction.outputs}}</td>
                                <td>{{transaction.size}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        data() {

            return {
                transactions: [],
                block: ''
            }
        },
        mounted() {
            Echo.channel('channel1')
                .listen('NewTransactionEvent', (resp) => {
                    this.transactions.unshift(resp.transaction);
                    if (this.transactions.length > 5) {
                        this.transactions.pop();
                    }
                    console.log(resp)

                }).listen('NewBlockEvent', (resp) => {
                this.block = resp.block;
            });
        },
        methods: {
            ss: function () {
                axios.get('/call-event').then(() => {

                })
            }
        }
    }
</script>
