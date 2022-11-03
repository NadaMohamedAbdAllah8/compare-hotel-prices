<?php
namespace App\Service;

require_once '../../vendor/autoload.php';

interface AdvertiserApiInterface
{

    // read advertisers
    public function callApi($url, $method);
}
