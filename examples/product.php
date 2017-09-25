<?php
use GuzzleHttp\Psr7\Response;
use Maverickslab\BigCommerce\BigCommerce;
use Maverickslab\BigCommerce\Http\Client\BigCommerceHttpClient;
use GuzzleHttp\Exception\GuzzleException;

$authToken = 'AUTH-TOKEN';
$clientId = 'CLIENT-ID';
$storeId = 'STORE-ID';
$clientSecret = 'CLIENT-SECRET';

$bigComhttpClient = BigCommerceHttpClient::createInstance($authToken, $clientId, $clientSecret, $storeId);

$bigComm = new BigCommerce($bigComhttpClient);

//Fetch first 50 products (without promises)
try {
    $reponse = $bigComm->product()->fetch(1, 50)->wait();
    $products = json_decode($reponse->getBody()->getContents());
} catch (GuzzleException $e) {
    echo $e->getMessage();
}

//Fetch first 50 products (with promises)
$bigComm->product()->fetch(1, 50)->then(function(Response $response){
    $products = json_decode($reponse->getBody()->getContents());
}, function(GuzzleException $e){
    echo $e->getMessage();
});