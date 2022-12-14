<?php
namespace App\Room;

use App\Config\Database;
use App\Objects\Room;

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

// prepare room object
$room = new Room($db);

// get room id

// reading data from form data
$data = $_POST;

// set room id to be deleted
$room->id = $data['id'];

// delete the room
if ($room->delete()) {

    // set response code - 200 ok
    http_response_code(200);

    // tell the user
    echo json_encode(array("message" => "Room was deleted."));
} else {
    // if unable to delete the room

    // set response code - 503 service unavailable
    http_response_code(503);

    // tell the user
    echo json_encode(array("message" => "Unable to delete room."));
}