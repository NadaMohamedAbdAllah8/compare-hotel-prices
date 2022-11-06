<?php
namespace App\Service;

use GuzzleHttp\Client as Client;

/**This class will execute class on API that have a similar
 * data for API 1,
 * this api https: //f704cb9e-bf27-440c-a927-4c8e57e3bad1.mock.pstmn.io/s1/availability
 */
class AdvertiserApi implements AdvertiserApiInterface
{

    // read advertisers' data from API
    // returns the data, or false
    public function callApi($url, $method)
    {
        // call the Guzzle API

        $client = new Client();

        $res = $client->request('GET', $url);

        if ($res->getStatusCode() != 200) {
            return false;
        }

        return $res->getBody();

    }
}