<?php

class Customer
{
    public function __set($attribute, $value)
    {
        $this->{$attribute} = $value;
    }

    public function get($attribute)
    {
        return $this->{$attribute};
    }
}
