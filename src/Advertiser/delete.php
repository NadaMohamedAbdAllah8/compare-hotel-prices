<?php
namespace App\Advertiser;

use App\Config\Database;
use App\Objects\Advertiser;

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

// set advertiser id to be deleted
$advertiser->id = $data['id'];

// delete the advertiser
if ($advertiser->delete()) {

    // set response code - 200 ok
    http_response_code(200);

    // tell the user
    echo json_encode(array("message" => "Advertiser was deleted."));
} else {
    // if unable to delete the advertiser

    // set response code - 503 service unavailable
    http_response_code(503);

    // tell the user
    echo json_encode(array("message" => "Unable to delete advertiser."));
}