<?php

namespace App\RoomTaxes;

use App\Config\Database;
use App\Objects\RoomTaxes;

require_once '../../vendor/autoload.php';

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$database = new Database();
$db = $database->getConnection();

$room_taxes = new RoomTaxes($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if (
    !empty($data->type) &&
    !empty($data->room_id) &&
    !empty($data->amount)
) {

    // set room_taxes property values
    $room_taxes->currency = $data->currency ?? " ";
    $room_taxes->type = $data->type;
    $room_taxes->amount = $data->amount;
    $room_taxes->room_id = $data->room_id;
    $room_taxes->created = date('Y-m-d H:i:s');

    // create the room_taxes
    if ($room_taxes->create()) {

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "RoomTaxes was created."));
    }

    // if unable to create the room_taxes, tell the user
    else {

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to create room_taxes."));
    }
}

// tell the user data is incomplete
else {

// set response code - 400 bad request
    http_response_code(400);

// tell the user
    echo json_encode(array("message" => "Unable to create room_taxes. Data is incomplete."));
}