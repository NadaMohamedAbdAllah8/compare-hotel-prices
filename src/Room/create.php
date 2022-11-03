<?php

namespace Src\Room;

use Src\Config\Database;
use Src\Objects\Room;

require_once '../../vendor/autoload.php';

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$database = new Database();
$db = $database->getConnection();

$room = new Room($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if (
    !empty($data->code) &&
    !empty($data->hotel_id) &&
    !empty($data->subtotal) &&
    !empty($data->total)
) {

    // set room property values
    $room->name = $data->name ?? " ";
    $room->code = $data->code;
    $room->hotel_id = $data->hotel_id;
    $room->subtotal = $data->subtotal;
    $room->total = $data->total;
    $room->created = date('Y-m-d H:i:s');

    // create the room
    if ($room->create()) {

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "Room was created."));
    }

    // if unable to create the room, tell the user
    else {

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to create room."));
    }
}

// tell the user data is incomplete
else {

// set response code - 400 bad request
    http_response_code(400);

// tell the user
    echo json_encode(array("message" => "Unable to create room. Data is incomplete."));
}