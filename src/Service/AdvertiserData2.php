<?php
namespace App\Service;

use App\Config\Database;
use App\Objects\Hotel;
use App\Objects\Room;
use App\Objects\RoomTaxes;
use Exception;

//require_once '../../vendor/autoload.php';

class AdvertiserData2 implements AdvertiserDataInterface
{
    private $advertiser_2_api;
    private $advertiser;

    public function __construct(AdvertiserApi $advertiser_2_api, $advertiser)
    {
        $this->advertiser_2_api = $advertiser_2_api;

        $this->advertiser = $advertiser;

    }

    /**The $data will have the same structure as advertiser API1
     * this API https://f704cb9e-bf27-440c-a927-4c8e57e3bad1.mock.pstmn.io/s1/availability
     */
    // store the data in the database
    /* Data format in json:
    "hotels": [
    {
    "name": "Hotel A",
    "stars": 4,
    "rooms": [
    {
    "code": "DBL-TWN",
    "name": "Double or Twin SUPERIOR",
    "net_rate": "143.00",
    "taxes": [
    {
    "amount": "10.00",
    "currency": "EUR",
    "type": "TAXESANDFEES"
    }
    ],
    "totalPrice": "153.00"
    },*/

    public function storeData()
    {
        // get data from API
        $data = $this->advertiser_2_api->callApi($this->advertiser['url'],
            $this->advertiser['method']);

        if ($data === false) {
            return false;
        }

        $decoded_data = json_decode($data);

        // store the data in the database
        $hotels_length = count($decoded_data->hotels);

        $hotels = $decoded_data->hotels;

        $database = new Database();

        $db = $database->getConnection();

        $db->beginTransaction();

        for ($h = 0; $h < $hotels_length; $h++) {
            // store hotel
            $hotel_id = $this->storeHotel($db, $hotels[$h]);

            if ($hotel_id === false) {
                $db->rollBack();

                // throw exception
                throw new Exception('Exception! Could not create hotel', 100);
            }

            // store rooms

            $hotel_rooms = $hotels[$h]->rooms;
            $hotel_rooms_length = count($hotel_rooms);

            for ($r = 0; $r < $hotel_rooms_length; $r++) {

                $room_id = $this->storeRoom($db, $hotel_rooms[$r], $hotel_id);

                if ($room_id === false) {
                    $db->rollBack();

                    // throw exception
                    throw new Exception('Exception! Could not create room', 100);

                }

                // store room taxes

                $room_taxes = $hotel_rooms[$r]->taxes;

                if (!is_array($room_taxes)) {
                    $room_taxes = [$room_taxes];
                }

                $room_taxes_length = count($room_taxes);

                for ($t = 0; $t < $room_taxes_length; $t++) {

                    $room_taxes_id = $this->storeRoomTaxes($db, $room_taxes[$t], $room_id);

                    if ($room_taxes_id === false) {
                        $db->rollBack();

                        // throw exception
                        throw new Exception('Exception! Could not create room taxes', 100);

                    }

                } // end of creating room taxes
            }
            // end of creating rooms

        } // end of creating hotels

        $db->commit();

    }

    private function storeHotel($db, $hotel_data)
    {
        $hotel = new Hotel($db);

        // set hotel property values

        $hotel->stars = $hotel_data->stars;
        $hotel->name = $hotel_data->name;
        $hotel->advertiser_id = $this->advertiser['id'];
        $hotel->created = date('Y-m-d H:i:s');

        $hotel_id = $hotel->create();

        if ($hotel_id !== false) {
            return $hotel_id;
        }

        return false;
    }

    private function storeRoom($db, $room_data, $hotel_id)
    {
        // create room
        $room = new Room($db);

        $room->name = $room_data->name ?? " ";
        $room->code = $room_data->code;
        $room->hotel_id = $hotel_id;
        $room->subtotal = $room_data->net_rate;
        $room->total = $room_data->totalPrice;
        $room->created = date('Y-m-d H:i:s');

        $room_id = $room->create();

        if ($room_id !== false) {
            return $room_id;
        }

        return false;

    }

    private function storeRoomTaxes($db, $room_tax_data, $room_id)
    {

        $room_tax = new RoomTaxes($db);

        $room_tax->currency = $room_tax_data->currency;
        $room_tax->type = $room_tax_data->type;
        $room_tax->amount = $room_tax_data->amount;
        $room_tax->room_id = $room_id;
        $room_tax->created = date('Y-m-d H:i:s');

        $room_tax_id = $room_tax->create();

        if ($room_tax_id !== false) {
            return $room_tax_id;
        }

        return false;

    }
}