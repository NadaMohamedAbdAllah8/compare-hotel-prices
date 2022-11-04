<?php
namespace App\Service;

use App\Config\Database;
use App\Objects\Advertiser;
use App\Objects\Hotel;
use App\Objects\Room;
use App\Objects\RoomTaxes;
use Exception;

//require_once '../../vendor/autoload.php';

class AdvertiserData1 implements AdvertiserDataInterface
{
    private $advertiser_1_api;
    private $advertiser;

    public function __construct(AdvertiserApi $advertiser_1_api, $advertiser)
    {
        $this->advertiser_1_api = $advertiser_1_api;

        $this->advertiser = $advertiser;

    }

    /**The $data will have the same structure as advertiser API1
     * this API https://f704cb9e-bf27-440c-a927-4c8e57e3bad1.mock.pstmn.io/s1/availability
     */
    // store the data in the database
    /* Data format in json:
     **"name": "Hotel A",
    "stars": 4,
    "rooms": [
    {
    "code": "DBL-TWN",
    "net_price": "140.00",
    "taxes": {
    "amount": "12.00",
    "currency": "EUR",
    "type": "TAXESANDFEES"
    },
    "total": "152.00"
    },]
    }, */

    public function storeData()
    {
        // call the function that will be the data in AdvertiserApi
        $data = $this->advertiser_1_api->callApi($this->advertiser['url'],
            $this->advertiser['method']);

        if ($data === false) {
            return false;
        }

        $decoded_data = json_decode($data);

        // store the data in the database
        $hotels_length = count($decoded_data->hotels);

        // echo $hotels_length;

        $hotels = $decoded_data->hotels;

        $database = new Database();

        $db = $database->getConnection();

        $db->beginTransaction();

        for ($h = 0; $h < $hotels_length; $h++) {
            // create hotel
            $hotel_id = $this->storeHotel($db, $hotels[$h]);

            if ($hotel_id === false) {
                $db->rollBack();

                // throw exception
                throw new Exception('Exception! Could not create hotel', 100);
                // throw new CannotCreateException('Hotel');
                //  return false;
            }

            // create rooms

            $hotel_rooms = $hotels[$h]->rooms;
            $hotel_rooms_length = count($hotel_rooms);

            for ($r = 0; $r < $hotel_rooms_length; $r++) {
                // create hotel
                $room_id = $this->storeRoom($db, $hotel_rooms[$r], $hotel_id);

                if ($room_id === false) {
                    $db->rollBack();

                    // throw exception
                    throw new Exception('Exception! Could not create room', 100);

                }

// create room taxes

                $room_taxes = $hotel_rooms[$r]->taxes;

                if (!is_array($room_taxes)) {
                    $room_taxes = [$room_taxes];
                }

                $room_taxes_length = count($room_taxes);

                for ($t = 0; $t < $room_taxes_length; $t++) {
                    // create hotel
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
        // create hotel
        $hotel = new Hotel($db);

// set hotel property values

        $hotel->stars = $hotel_data->stars;
        $hotel->name = $hotel_data->name;
        $hotel->advertiser_id = $this->advertiser['id'];
        $hotel->created = date('Y-m-d H:i:s');

        $hotel_id = $hotel->create();

// create the hotel
        if ($hotel_id !== false) {
            return $hotel_id;
        }

        return false;
    }

    private function storeRoom($db, $room_data, $hotel_id)
    {
        // create room
        $room = new Room($db);

        // set room property values
        $room->name = $room_data->name ?? " ";
        $room->code = $room_data->code;
        $room->hotel_id = $hotel_id;
        $room->subtotal = $room_data->net_price;
        $room->total = $room_data->total;
        $room->created = date('Y-m-d H:i:s');

        $room_id = $room->create();

// create the room
        if ($room_id !== false) {
            return $room_id;
        }

        return false;

    }

    private function storeRoomTaxes($db, $room_tax_data, $room_id)
    {

        // create room tax
        $room_tax = new RoomTaxes($db);

        // set room property values
        $room_tax->currency = $room_tax_data->currency;
        $room_tax->type = $room_tax_data->type;
        $room_tax->amount = $room_tax_data->amount;
        $room_tax->room_id = $room_id;
        $room_tax->created = date('Y-m-d H:i:s');

        $room_tax_id = $room_tax->create();

// create the room
        if ($room_tax_id !== false) {
            return $room_tax_id;
        }

        return false;

    }
}