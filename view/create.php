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

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="7" style="text-align: center;" @click="addNewRow"><i class="fa fa-plus" aria-hidden="true"></i> ADD NEW</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-right">TOTAL</th>
                                        <th class="text-center">{{ total_quantity }}</th>
                                        <th></th>
                                        <th class="text-right">{{ total_net_amount }}</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="col-sm-12 col-md-8">
                            <div class="form-group">
                                <label>Last Bal</label>
                                <input type="text" v-model="last_balance" @keyup="calculateOverallBalance" class="form-control" placeholder="Last Balance">
                            </div>
                            <div class="form-group" v-for="(record, index) in form.records">
                                <label for="">Record On.</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <date-picker v-model="form.records[index].record_date"></date-picker>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" v-model="form.records[index].record_amount" @keyup="calculateOverallBalance" class="form-control" placeholder="Record Amount">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <a href="" @click.prevent="addNewRecordRow" class="btn btn-md btn-info"><i class="fa fa-plus" aria-hidden="true"></i> ADD NEW</a>
                            </div>
                        </div>


                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>DISCOUNT</th>
                                        <th><input type="text" v-model="form.discount_percent" @keyup="calculateDiscount(0)" placeholder="%" class="form-control"></th>
                                        <th><input type="text" v-model="form.discount" @keyup="calculateDiscount(1)" placeholder="Discount" class="form-control"></th>
                                    </tr>
                                    <tr>
                                        <th>CST/VAT</th>
                                        <th><input type="text" v-model="form.vat_percent" @keyup="calculateVAT(0)" placeholder="%" class="form-control"></th>
                                        <th><input type="text" v-model="form.vat" @keyup="calculateVAT(1)" placeholder="CST/VAT" class="form-control"></th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th>NET AMOUNT</th>
                                        <th><input type="text" v-model="grand_total" readonly class="form-control"></th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th>Last Balance</th>
                                        <th><input type="text" v-model="prev_balance" readonly class="form-control"></th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th>GRAND TOTAL</th>
                                        <th><input type="text" v-model="final_balance" readonly class="form-control"></th>
                                    </tr>
                                </table>
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
                        city: '',
                        records: [],
                        discount_percent: '',
                        discount: '',
                        vat_percent: '',
                        vat: '',
                    },
                    customers: [],
                    total_net_amount: '',
                    total_quantity: '',
                    grand_total_after_discount: '',
                    grand_total: '',
                    last_balance: '',
                    prev_balance: '',
                    final_balance: ''
                }
            },
            methods: {
                showCity: function() {
                    const customer_id = this.form.customer_id;
                    const customer = this.customers.find(function (row) {
                        return row.id === customer_id;
                    });
                    this.form.city = customer ? customer.city : '';
                },
                fetchCustomers: function() {
                    const self = this;
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
                    const last_id = this.form.items.length + 1;
                    this.form.items.push({
                        id: last_id,
                        item_name: '',
                        unit: '',
                        quantity: '',
                        rate: '',
                        net_amount: ''
                    });
                },
                addNewRecordRow: function () {
                    const newRow = {
                        record_date: new Date(),
                        record_amount: '',
                    };
                    this.form.records.push(newRow);
                },
                calculateDiscount: function (flag) {
                    let discount = 0;
                    const net_total_amount = isNaN(parseFloat(this.total_net_amount)) ? 0 : parseFloat(this.total_net_amount);
                    if (flag === 0) {
                        const rate = this.form.discount_percent;
                        const ratio = rate / 100;
                        discount = ratio * net_total_amount;
                        this.form.discount = discount.toFixed(2);
                    }else {
                        discount = this.form.discount;
                        this.form.discount_percent = '';
                    }
                    this.calculateNetTotalAfterDiscount();
                    this.calculateGrandTotal();
                    this.calculateFinalBalance();
                },
                calculateNetTotalAfterDiscount: function () {
                    const discount = isNaN(parseFloat(this.form.discount)) ? 0 : parseFloat(this.form.discount);
                    const net_total_amount = isNaN(parseFloat(this.total_net_amount)) ? 0 : parseFloat(this.total_net_amount);
                    this.grand_total_after_discount = this.grand_total = net_total_amount - discount;
                },
                calculateVAT: function (flag) {
                    let vat = 0;
                    const grand_total_after_discount = isNaN(parseFloat(this.grand_total_after_discount)) ? 0 : parseFloat(this.grand_total_after_discount);
                    if (flag === 0) {
                        const rate = this.form.vat_percent;
                        const ratio = rate / 100;
                        vat = ratio * grand_total_after_discount;
                        this.form.vat = vat.toFixed(2);
                    } else {
                        this.form.vat_percent = '';
                    }
                    this.calculateGrandTotal();
                    this.calculateFinalBalance();
                },
                calculateGrandTotal: function () {
                    const vat = isNaN(parseFloat(this.form.vat)) ? 0 : parseFloat(this.form.vat);
                    const grand_total_after_discount = isNaN(parseFloat(this.grand_total_after_discount)) ? 0 : parseFloat(this.grand_total_after_discount);
                    this.grand_total = grand_total_after_discount + vat;
                },
                calculateOverallBalance: function () {
                    let last_balance = isNaN(parseFloat(this.last_balance)) ? 0 : parseFloat(this.last_balance);
                    let total_record_amount = this.form.records.reduce(function (sum, row) {
                        return isNaN(parseFloat(row.record_amount)) ? sum : sum + parseFloat(row.record_amount);
                    }, 0)
                    const prev_balance = last_balance - total_record_amount;
                    this.prev_balance = prev_balance.toFixed(2);
                    this.calculateFinalBalance();
                },
                calculateFinalBalance: function () {
                    let prev_balance = isNaN(parseFloat(this.prev_balance)) ? 0 : parseFloat(this.prev_balance);
                    let grand_total = isNaN(parseFloat(this.grand_total)) ? 0 : parseFloat(this.grand_total);
                    let final_balance = grand_total + prev_balance;
                    this.final_balance = final_balance.toFixed(2);
                },
                calculateNetAmount: function(index) {
                    const targetItem = this.form.items[index]
                    if (targetItem) {
                        const quantity = isNaN(parseFloat(targetItem.quantity)) ? 0 : parseFloat(targetItem.quantity);
                        const rate = isNaN(parseFloat(targetItem.rate)) ? 0 : parseFloat(targetItem.rate);
                        const net_amount = quantity * rate;
                        this.form.items[index].net_amount = net_amount.toFixed(2);

                        this.calculateTotalQuantity();
                        this.calculateTotalNetAmount();
                        this.calculateNetTotalAfterDiscount();
                        this.calculateGrandTotal();
                        this.calculateFinalBalance();
                    }
                },
                calculateTotalQuantity: function() {
                    const total_quantity = this.form.items.reduce(function (sum, row) {
                        return isNaN(parseFloat(row.quantity)) ? sum : sum + parseFloat(row.quantity);
                    }, 0);
                    this.total_quantity = parseFloat(total_quantity).toFixed(2)
                },
                calculateTotalNetAmount: function() {
                    const total_net_amount = this.form.items.reduce(function (sum, row) {
                        return isNaN(parseFloat(row.net_amount)) ? sum : sum + parseFloat(row.net_amount);
                    }, 0);
                    this.total_net_amount = parseFloat(total_net_amount).toFixed(2)
                }
            },
            created() {
                this.fetchCustomers();
            }
        });
    </script>
</body>

</html>