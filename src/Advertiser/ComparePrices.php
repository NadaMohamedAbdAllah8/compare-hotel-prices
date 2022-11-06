<?php

// instantiate database and hotel object
namespace App\Advertiser;

use App\Config\Database;
use App\Objects\Hotel;
use App\Objects\Room;

require_once '../../vendor/autoload.php';

$database = new Database();

$db = $database->getConnection();

// reading data from form data
$data = $_POST;

if (!isset($_POST['id'])) {
// set response code - 404 Not found
    http_response_code(404);

// tell the user no advertisers found
    echo json_encode(
        array("message" => "No advertisers were chosen.")
    );

    return;
}

$advertisers_ids = $data['id'];
$advertisers_length = count($data['id']);

$inserted_hotels_names = [];

//$rooms_hash_table = [[]];

for ($i = 0; $i < $advertisers_length; $i++) {
    // get the advertiser's hotels

    // initialize object
    $hotel = new Hotel($db);

    // read hotels

    $hotels = $hotel->readWhereAdvertiser($advertisers_ids[$i]);

    $hotels_length = count($hotels);

    for ($h = 0; $h < $hotels_length; $h++) {

        // initialize object
        $room = new Room($db);

        // query rooms
        $rooms = $room->readWhereHotel($hotels[$h]['id']);

        $rooms_length = count($rooms);

        if (in_array($hotels[$h]['name'], $inserted_hotels_names)) {
            for ($r = 0; $r < $rooms_length; $r++) {
                $room_code = $rooms[$r]['code'];

                $existing_room_info = $rooms_hash_table[$room_code];

                // compare if the room's code is already inserted for the same hotel
                if (array_key_exists($rooms[$r]['code'], $rooms_hash_table)
                    && $existing_room_info['hotel_name'] == $hotels[$h]['name']) {
                    // compare the totals
                    $new_room_info = $rooms[$r];

                    // replace
                    if ($new_room_info['total'] < $existing_room_info['total']) {
                        $rooms_hash_table[$rooms[$r]['code']] = [
                            'total' => $new_room_info['total'],
                            'room_id' => $new_room_info['id'],
                            'hotel_name' => $hotels[$h]['name']];
                    }

                } else {
                    // insert the room

                    $rooms_hash_table[$rooms[$r]['code']] = [
                        'total' => $rooms[$r]['total'],
                        'room_id' => $rooms[$r]['id'],
                        'hotel_name' => $hotels[$h]['name']];
                }
            }

        } else {
            // insert the hotel name in inserted_hotels_names to mark the hotel as inserted
            // in the hash table
            array_push($inserted_hotels_names, $hotels[$h]['name']);

            for ($r = 0; $r < $rooms_length; $r++) {

                $rooms_hash_table[$rooms[$r]['code']] = [
                    'total' => $rooms[$r]['total'],
                    'room_id' => $rooms[$r]['id'],
                    'hotel_name' => $hotels[$h]['name']];
            }

        }
    }
}

// sort by total cheapest to most expensive
asort($rooms_hash_table);

http_response_code(200);

echo json_encode($rooms_hash_table);

//$rooms_hash_table = [[]];

// room_id=>total

//$rooms['code'] = ['total' => 0, 'room_id' => 0];
