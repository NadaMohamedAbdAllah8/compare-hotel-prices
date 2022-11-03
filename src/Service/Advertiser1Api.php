<?php
namespace Src\Service;

use Api\Objects\AdvertiserApiInterface;

/**This class will execute class on API that have a similar
 * data for API 1,
 * this api https: //f704cb9e-bf27-440c-a927-4c8e57e3bad1.mock.pstmn.io/s1/availability
 */
class AdvertiserApi1 implements AdvertiserApiInterface
{

    // read advertisers
    public function callApi()
    {
        //call the Guzzle API

        $data = "data from the call";

        // call adapter 1 to store the info in the database
    }
}