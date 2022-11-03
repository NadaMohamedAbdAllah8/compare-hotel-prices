<?php
namespace Src\Service;

use Api\Objects\AdvertiserApiInterface;

/**This class will execute class on API that have a similar
 * data for API 2,
 * this api https: //f704cb9e-bf27-440c-a927-4c8e57e3bad1.mock.pstmn.io/s2/availability
 */
class AdvertiserApi2 implements AdvertiserApiInterface
{

    // read advertisers
    public function callApi()
    {
        //call the Guzzle API

        $data = "data from the call";

        // call adapter 2 to store the info in the database
    }
}