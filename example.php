<?php
/**
 * Created by PhpStorm.
 * User: bsevgin
 * Date: 22.12.2017
 * Time: 12:05
 */

session_start();
require_once("GarantiPos.php");

if(!isset($_SESSION['orderNumber']) || !empty($_SESSION['orderNumber'])){
    $_SESSION['orderNumber'] = uniqid();
}

//Sipariş bilgilerinizi buraya gireceksiniz
$paymentType = "creditcard"; //Kredi kartı için: "creditcard", GarantiPay için: "garantipay"
$params      = [
    'companyName'      => "XXX",
    'orderNo'          => $_SESSION['orderNumber'],
    'amount'           => "1.00",
    'installmentCount' => "",
    'currencyCode'     => "949", //TRY=949, USD=840, EUR=978, GBP=826, JPY=392
    'customerIP'       => "127.0.0.1",
    'customerEmail'    => "xxx@gmail.com",
    'cardName'         => "XXX XXX", //opsiyonel
    'cardNumber'       => "XXXXXXXXXXXXXXXX",
    'cardExpiredMonth' => "XX",
    'cardExpiredYear'  => "XX",
    'cardCvv'          => "XXX",
];

$pos = new GarantiPos($params);

$action = isset($_GET['action'])?$_GET['action']:false;
if($action){
    //$pos->debugMode = true;
    print_r($pos->callback($action,$paymentType));
    unset($_SESSION['orderNumber']); //sipariş tamamlandığı için session siliniyor
}
else{
    //$pos->debugMode = true;
    $pos->pay($paymentType);
}
