<?php

class Invoice
{
    // table fields
    public $id;
    public $customer_id;
    public $date;
    public $discount;
    public $vat;
    public $last_balance;
    public $grand_total;
    public $final_balance;
    public $items;
    public $records;
    // constructor set default value
    function __construct()
    {
        
    }
}
