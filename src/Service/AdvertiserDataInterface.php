<?php

namespace App\Service;

require_once '../../vendor/autoload.php';

interface AdvertiserDataInterface
{

    // store into the database
    public function storeData();
}
