<?php

namespace App\Hotel;

use App\Config\Database;
use App\Objects\Hotel;

require_once '../../vendor/autoload.php';

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$database = new Database();
$db = $database->getConnection();

$hotel = new Hotel($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if (
    !empty($data->name) &&
    !empty($data->advertiser_id)
) {

    // set hotel property values
    $hotel->stars = $data->stars ?? " ";
    $hotel->name = $data->name;
    $hotel->advertiser_id = $data->advertiser_id;
    $hotel->created = date('Y-m-d H:i:s');

    // create the hotel
    if ($hotel->create()) {

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "Hotel was created."));
    }

    // if unable to create the hotel, tell the user
    else {

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to create hotel."));
    }
}

// tell the user data is incomplete
else {

// set response code - 400 bad request
    http_response_code(400);

// tell the user
    echo json_encode(array("message" => "Unable to create hotel. Data is incomplete."));
}
