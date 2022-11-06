<?php
namespace App\Advertiser;

use App\Config\Database;
use App\Objects\Advertiser;
use App\Service\AdvertiserApi;
use App\Service\AdvertiserData2;
use Exception;

require_once '../../vendor/autoload.php';

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
$database = new Database();

$db = $database->getConnection();

// prepare advertiser object
$advertiser = new Advertiser($db);

// get advertiser id

// reading data from form data
$data = $_POST;

// set advertiser id to be stored
$advertiser->id = $data['id'];

// read the advertiser
$read_advertiser = $advertiser->readOne();

if ($read_advertiser) {

    $advertiser_api = new AdvertiserApi();

    $advertiser_2_data = new AdvertiserData2($advertiser_api, $read_advertiser);

    try {
        $db->beginTransaction();

        if ($advertiser_2_data->storeData() === false) {
            // set response code - 503 service unavailable
            http_response_code(503);

            // tell the user
            echo json_encode(array("message" => "Unable to read advertiser's data from API."));

        }

        $db->commit();
        // set response code - 200 ok
        http_response_code(200);

        // tell the user
        echo json_encode(array("message" => "Advertiser's data was stored."));
    } catch (Exception $e) {
        $db->rollBack();

        // set response code - 503
        http_response_code(503);

        echo json_encode(array("message" => "Could not store data." . $e->getMessage()));

    }
} else {
    // if unable to store the advertiser

    // set response code - 503 service unavailable
    http_response_code(503);

    // tell the user
    echo json_encode(array("message" => "Unable to read advertiser."));
}