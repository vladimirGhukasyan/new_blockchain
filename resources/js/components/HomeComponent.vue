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
                                <td><a href="#" data-toggle="modal" data-target="#blockModal">{{block.hash}}</a></td>
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
                                <th>Time</th>
                                <th>Size</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="transaction in transactions">
                                <td>{{transaction.hash}}</td>
                                <td>{{transaction.inputs}}</td>
                                <td>{{transaction.outputs}}</td>
                                <td>{{ transaction.time | moment("ll h:mm:ss a") }}</td>
                                <td>{{transaction.size}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="blockModal" ref="blockModal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Block</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row w-100">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <th> size</th>
                                        <td>{{ block.size }}</td>
                                    </tr>
                                    <tr>
                                        <th> totalBTCSent</th>
                                        <td>{{ block.totalBTCSent }}</td>
                                    </tr>
                                    <tr>
                                        <th> estimatedBTCSent</th>
                                        <td>{{ block.estimatedBTCSent }}</td>
                                    </tr>
                                    <tr>
                                        <th> blockIndex</th>
                                        <td>{{ block.blockIndex }}</td>
                                    </tr>
                                    <tr>
                                        <th> mrklRoot</th>
                                        <td>{{ block.mrklRoot }}</td>
                                    </tr>
                                    <tr>
                                        <th> hash</th>
                                        <td>{{ block.hash }}</td>
                                    </tr>
                                    <tr>
                                        <th> prevBlockIndex</th>
                                        <td>{{ block.prevBlockIndex }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
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
                block: '',
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
    }
</script>
