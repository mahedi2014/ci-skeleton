<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Mahedi Azad
 * Date: 24-Mar-16
 * Time: 12:14 PM
 */
class Email_Config
{
    function index()
    {
        return mail('mahedi2014@gmail.com', 'My Subject', 'test');
    }

}