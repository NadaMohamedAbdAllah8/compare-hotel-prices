<?php
namespace App\Service;

use App\Objects\Advertiser;

//require_once '../../vendor/autoload.php';

class AdvertiserData1 implements AdvertiserDataInterface
{
    private $advertiser_1_api;
    private $advertiser;

    public function __construct(AdvertiserApi $advertiser_1_api, $advertiser)
    {
        $this->advertiser_1_api = $advertiser_1_api;
        $this->advertiser = $advertiser;

        //echo 'in construct AdvertiserData1  ';
        // var_dump($advertiser);

    }

    /**The $data will have the same structure as advertiser API1
     * this API https://f704cb9e-bf27-440c-a927-4c8e57e3bad1.mock.pstmn.io/s1/availability
     */
    // store the data in the database
    public function storeData()
    {

        // echo 'hello in storeData AdvertiserData1  ---url=';
        // var_dump($this->advertiser['url']);

        // call the function that will be the data in AdvertiserApi
        $data = $this->advertiser_1_api->callApi($this->advertiser['url'],
            $this->advertiser['method']);

        var_dump($data);

        /* Data format:
         **"name": "Hotel A",
        "stars": 4,
        "rooms": [
        {
        "code": "DBL-TWN",
        "net_price": "140.00",
        "taxes": {
        "amount": "12.00",
        "currency": "EUR",
        "type": "TAXESANDFEES"
        },
        "total": "152.00"
        },]
        }, */

        // store the data in the database
    }
}