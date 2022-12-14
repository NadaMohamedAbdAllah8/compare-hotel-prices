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

$database = new Database();
$db = $database->getConnection();

$advertiser = new Advertiser($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));
// reading data from form data
$data = $_POST;

// make sure data is not empty
if (
    !empty($data['url']) &&
    !empty($data['method'])
) {

    // set advertiser property values
    $advertiser->name = $data['name'] ?? " ";
    $advertiser->url = $data['url'];
    $advertiser->method = $data['method'];
    $advertiser->created = date('Y-m-d H:i:s');

    // create the advertiser
    if ($advertiser->create()) {

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "Advertiser was created."));
    }

    // if unable to create the advertiser, tell the user
    else {

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to create advertiser."));
    }
} else {
    // tell the user data is incomplete

    // set response code - 400 bad request
    http_response_code(400);

// tell the user
    echo json_encode(array("message" => "Unable to create advertiser. Data is incomplete."));
}