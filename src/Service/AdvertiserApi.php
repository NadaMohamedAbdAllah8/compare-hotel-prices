<?php
namespace App\Service;

require_once '../../vendor/autoload.php';

use GuzzleHttp\Client as Client;

/**This class will execute class on API that have a similar
 * data for API 1,
 * this api https: //f704cb9e-bf27-440c-a927-4c8e57e3bad1.mock.pstmn.io/s1/availability
 */
class AdvertiserApi implements AdvertiserApiInterface
{

    // read advertisers
    public function callApi($url, $method)
    {echo $url;
        // call the Guzzle API

        $data = "data from the call";

        $client = new Client();

        $res = $client->request('GET', $url);

        echo $res->getStatusCode();
// "200"
        echo $res->getHeader('content-type')[0];
// 'application/json; charset=utf8'
        echo $res->getBody();

        return $data;
    }
}