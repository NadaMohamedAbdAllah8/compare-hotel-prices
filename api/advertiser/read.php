<?php

namespace Api\Advertiser;

use Api\Config\Database;
use Api\Objects\Advertiser;
use PDO;

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
$stmt = $advertiser->read();

$num = $stmt->rowCount();

// check if more than 0 record found
if ($num > 0) {

    // advertisers array
    $advertisers_arr = array();
    $advertisers_arr["records"] = array();

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);

        $advertiser_item = array(
            "id" => $id,
            "name" => $name,
            "url" => $url,
            "method" => $method,
        );

        array_push($advertisers_arr["records"], $advertiser_item);
    }

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