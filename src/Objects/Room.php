<?php
namespace App\Objects;

use App\Objects\DatabaseModel;
use PDO;

class Room implements DatabaseModel
{

    // database connection and table name
    private $conn;
    private $table_name = "rooms";

    // object properties
    public $id;
    public $name;
    public $code;
    public $hotel_id;
    public $subtotal;
    public $total;
    public $created;
    public $modified;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // read rooms
    public function read()
    {
        // select all query
        $query = "SELECT id, name, code, hotel_id, subtotal, total  FROM " . $this->table_name .
            "  ORDER BY created DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    // create room
    public function create()
    {
        // query to insert record
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                name=:name, hotel_id=:hotel_id, code=:code,subtotal=:subtotal,total=:total, created=:created, modified=:modified";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->code = htmlspecialchars(strip_tags($this->code));
        $this->hotel_id = htmlspecialchars(strip_tags($this->hotel_id));
        $this->subtotal = htmlspecialchars(strip_tags($this->subtotal));
        $this->total = htmlspecialchars(strip_tags($this->total));
        $this->created = htmlspecialchars(strip_tags(date("Y-m-d H:i:s")));
        $this->modified = htmlspecialchars(strip_tags(date("Y-m-d H:i:s")));

        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":code", $this->code);
        $stmt->bindParam(':hotel_id', $this->hotel_id);
        $stmt->bindParam(":subtotal", $this->subtotal);
        $stmt->bindParam(":total", $this->total);
        $stmt->bindParam(":created", $this->created);
        $stmt->bindParam(":modified", $this->modified);

        // execute query
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;

    }

// select roomss based on hotel id
    public function readWhereHotel($hotel_id)
    {
        // select all query
        $query = "SELECT id, name, code, total, hotel_id  FROM " . $this->table_name .
            "  where hotel_id = ?";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->hotel_id = htmlspecialchars(strip_tags($this->hotel_id));

        // bind id of record to delete
        $stmt->bindParam(1, $hotel_id);

        // execute query
        if ($stmt->execute()) {

            $num = $stmt->rowCount();

// check if more than 0 record found
            if ($num > 0) {

                // rooms array
                $rooms_arr = array();

                // retrieve our table contents
                // fetch() is faster than fetchAll()
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // extract row
                    // this will make $row['name'] to
                    // just $name only
                    extract($row);

                    $room_item = array(
                        "id" => $id,
                        "name" => $name,
                        "code" => $code,
                        "total" => $total,
                    );

                    array_push($rooms_arr, $room_item);
                }

                return $rooms_arr;

            }

        }
        return false;
    }

    // delete the room
    public function delete()
    {

        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind id of record to delete
        $stmt->bindParam(1, $this->id);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

}