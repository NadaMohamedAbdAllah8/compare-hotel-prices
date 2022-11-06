<?php

namespace App\Advertiser;

use App\Config\Database;
use App\Objects\Advertiser;

require_once '../../vendor/autoload.php';

// require __DIR__ . "../vendor/autoload.php";

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// instantiate database and advertiser object
$database = new Database();

$db = $database->getConnection();

// initialize object
$advertiser = new Advertiser($db);

// query advertisers
$advertisers_arr = $advertiser->read();

if ($advertisers_arr !== false) {
    // set response code - 200 OK
    http_response_code(200);

    // show advertisers data in json format
    echo json_encode($advertisers_arr);
} else {

    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no advertisers found
    echo json_encode(
        array("message" => "No advertisers found.")
    );
}
