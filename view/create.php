<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>libs/bootstrap.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>libs/vue2-datepicker.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>libs/bootstrap-datepicker.min.css">
    <link href="<?php echo BASE_URL; ?>libs/fontawesome/css/font-awesome.css" rel="stylesheet" />
    <script src="<?php echo BASE_URL; ?>libs/jquery.min.js"></script>
    <script src="<?php echo BASE_URL; ?>libs/bootstrap-datepicker.min.js"></script>
    <style type="text/css">
        .wrapper {
            width: 500px;
            margin: 0 auto;
        }
        .mx-datepicker{
            width: 100% !important;
        }
    </style>
</head>

<body>
    <div class="container" id="app">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h2>Add Invoice</h2>
                </div>
                <p>Please fill this form and submit to add invoice record in the database.</p>
                <form action="<?php echo BASE_URL; ?>store" method="post">
                    <div class="row">
                        <div class="col-sm-12 col-md-8">
                            <div class="form-group">
                                <label>Customer</label>
                                <select v-model="form.customer_id" @change="showCity" class="form-control">
                                    <option value="">Select ...</option>
                                    <option v-if="customers.length > 0" v-for="customer in customers" :key="customer.id" :value="customer.id">{{ customer.name }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>City</label>
                                <textarea v-model="form.city" rows="2" class="form-control" readonly></textarea>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <div class="form-group">
                                <label>Invoice No.</label>
                                <input type="text" v-model="form.invoice_id" value="" class="form-control" placeholder="Invoice No">
                            </div>
                            <div class="form-group">
                                <label>Date</label>
                                <date-picker v-model="form.date"></date-picker>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SR</th>
                                        <th>ITEM NAME</th>
                                        <th>UNIT</th>
                                        <th>QUANTITY</th>
                                        <th>RATE</th>
                                        <th>NET AMOUNT</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="item_list">
                                    <tr v-for="(item, index) in form.items" :key="index">
                                        <th>{{ index + 1 }}</th>
                                        <th>
                                            <input type="text" v-model="form.items[index].item_name" placeholder="Item Name" class="form-control" />
                                        </th>
                                        <th>
                                            <input type="text" v-model="form.items[index].unit" placeholder="Unit" class="form-control" />
                                        </th>
                                        <th>
                                            <input type="text" @keyup="calculateNetAmount(index)" v-model="form.items[index].quantity" placeholder="Quantity" class="form-control" />
                                        </th>
                                        <th>
                                            <input type="text" @keyup="calculateNetAmount(index)" v-model="form.items[index].rate" placeholder="Rate" class="form-control" />
                                        </th>
                                        <th>
                                            <input type="text" v-model="form.items[index].net_amount" placeholder="Net Amount" class="form-control" />
                                        </th>
                                        <th>
                                            <a class="text-danger">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </a>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-right">TOTAL</th>
                                        <th class="text-center">{{ total_quantity }}</th>
                                        <th></th>
                                        <th class="text-right">{{ total_net_amount }}</th>
                                        <th></th>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <th colspan="7" style="text-align: center;" @click="addNewRow"><i class="fa fa-plus" aria-hidden="true"></i> ADD NEW</th>
                                </tfoot>
                            </table>
                        </div>

                        <div class="col-sm-12 col-md-8">
                            <div class="form-group">
                                <label>Last Bal</label>
                                <input type="text" v-model="form.last_balance" value="" class="form-control" placeholder="Last Balance">
                            </div>
                        </div>
                    </div>
                    <input type="submit" name="addbtn" class="btn btn-primary" value="Submit">
                    <a href="<?php echo BASE_URL; ?>" class="btn btn-default">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    <script src="<?php echo BASE_URL; ?>libs/vue.js"></script>
    <script src="<?php echo BASE_URL; ?>libs/vue2-datepicker.min.js"></script>

    <script>
        var app = new Vue({
            el: '#app',
            components: {},
            data: function() {
                return {
                    form: {
                        customer_id: '',
                        items: [],
                        date: new Date,
                        invoice_id: '',
                        city: ''
                    },
                    customers: [],
                    total_net_amount: '',
                    total_quantity: ''
                }
            },
            methods: {
                showCity: function() {
                    var customer_id = this.form.customer_id;
                    var customer = this.customers.find(function(row) {
                        return row.id == customer_id;
                    })
                    this.form.city = customer ? customer.city : '';
                },
                fetchCustomers: function() {
                    var self = this;
                    $.ajax({
                        method: 'get',
                        url: '<?php echo BASE_URL; ?>customers/all',
                        dataType: 'json',
                        success: function(response) {
                            self.customers = response;
                        }
                    })
                },
                addNewRow: function() {
                    var last_id = this.form.items.length + 1;
                    this.form.items.push({
                        id: last_id,
                        item_name: '',
                        unit: '',
                        quantity: '',
                        rate: '',
                        net_amount: ''
                    });
                },
                calculateNetAmount: function(index) {
                    var targetItem = this.form.items[index]
                    if (targetItem) {
                        var quantity = isNaN(parseFloat(targetItem.quantity)) ? 0 : parseFloat(targetItem.quantity);
                        var rate = isNaN(parseFloat(targetItem.rate)) ? 0 : parseFloat(targetItem.rate);
                        var net_amount = quantity * rate;
                        this.form.items[index].net_amount = parseFloat(net_amount).toFixed(2);

                        this.calculateTotalQuantity();
                        this.calculateTotalNetAmount();
                    }
                },
                calculateTotalQuantity: function() {
                    var total_quantity = this.form.items.reduce(function(sum, row) {
                        return isNaN(parseFloat(row.quantity)) ? sum : sum + parseFloat(row.quantity);
                    }, 0);
                    this.total_quantity = parseFloat(total_quantity).toFixed(2)
                },
                calculateTotalNetAmount: function() {
                    var total_net_amount = this.form.items.reduce(function(sum, row) {
                        return isNaN(parseFloat(row.net_amount)) ? sum : sum + parseFloat(row.net_amount);
                    }, 0);
                    this.total_net_amount = parseFloat(total_net_amount).toFixed(2)
                }
            },
            created() {
                this.fetchCustomers();
            }
        })
    </script>
</body>

</html>