<?php

require_once 'Config.php';

class Controller
{
    function __construct()
    {
    }

    // page redirection
    public function pageRedirect($url)
    {
        header('Location:' . BASE_URL. $url);
    }
}
