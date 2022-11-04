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

    // read advertisers' data from API
    // returns the data, or false
    public function callApi($url, $method)
    {
        // call the Guzzle API

        $client = new Client();

        $res = $client->request('GET', $url);

//         echo 'res status code=' . $res->getStatusCode();
//  "200"
//         echo 'content type=' . $res->getHeader('content-type')[0];
// 'application/json; charset=utf8'

        if ($res->getStatusCode() != 200) {
            return false;
        }

        return $res->getBody();

    }
}
