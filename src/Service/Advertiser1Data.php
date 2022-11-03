<?php
namespace Src\Service;

use Api\Objects\Advertiser;

class Advertiser1Data implements AdvertiserData
{
    private $advertiser_1_api;
    private $advertiser;

    public function __construct(AdvertiserApi1 $advertiser_1_api, Advertiser $advertiser)
    {
        $this->advertiser_1_api = $advertiser_1_api->advertiser_1_api;
        $this->advertiser = $advertiser->advertiser_1_api;
    }

    /**The $data will have the same structure as advertiser API1
     * this API https://f704cb9e-bf27-440c-a927-4c8e57e3bad1.mock.pstmn.io/s1/availability
     */
    // store the data in the database
    public function storeData($data)
    {

        // call the function that will be the data in AdvertiserApi1
        $data = "the call";

        // store the data
    }
}