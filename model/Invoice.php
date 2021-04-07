<?php

class Invoice
{
    // table fields
    public $id;
    public $customer_id;
    public $amount;
    // message string
    public $discount;
    public $vat;
    public $net_amount;
    public $dispatched_per;
    public $remark;
    public $invoice_date;
    public $created_at;
    // constructor set default value
    function __construct()
    {
        
    }
}
