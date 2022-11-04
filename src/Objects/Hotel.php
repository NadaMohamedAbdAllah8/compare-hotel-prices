<?php
namespace App\Objects;

use App\Objects\DatabaseModel;
use PDO;

class Hotel implements DatabaseModel
{

    // database connection and table name
    private $conn;
    private $table_name = "hotels";

    // object properties
    public $id;
    public $name;
    public $advertiser_id;
    public $stars;
    public $created;
    public $modified;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // read hotels
    public function read()
    {
        // select all query
        $query = "SELECT id, name, advertiser_id, stars  FROM " . $this->table_name .
            "  ORDER BY created DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    // select hotels based on advertiser id
    public function readWhereAdvertiser($advertiser_id)
    {
        // select all query
        $query = "SELECT id, name, advertiser_id, stars  FROM " . $this->table_name .
            "  where advertiser_id = ?";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->advertiser_id = htmlspecialchars(strip_tags($this->advertiser_id));

        // bind id of record to delete
        $stmt->bindParam(1, $advertiser_id);

        // execute query
        if ($stmt->execute()) {

            $num = $stmt->rowCount();

// check if more than 0 record found
            if ($num > 0) {

                // hotels array
                $hotels_arr = array();

                // retrieve our table contents
                // fetch() is faster than fetchAll()
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // extract row
                    // this will make $row['name'] to
                    // just $name only
                    extract($row);

                    $hotel_item = array(
                        "id" => $id,
                        "name" => $name,
                    );

                    array_push($hotels_arr, $hotel_item);
                }

                return $hotels_arr;

            }

        }
        return false;
    }

    // create hotel
    public function create()
    {

        // query to insert record
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                name=:name, advertiser_id=:advertiser_id, stars=:stars, created=:created, modified=:modified";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->advertiser_id = htmlspecialchars(strip_tags($this->advertiser_id));
        $this->stars = htmlspecialchars(strip_tags($this->stars));
        $this->created = htmlspecialchars(strip_tags(date("Y-m-d H:i:s")));
        $this->modified = htmlspecialchars(strip_tags(date("Y-m-d H:i:s")));

        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(':advertiser_id', $this->advertiser_id);
        $stmt->bindParam(":stars", $this->stars);
        $stmt->bindParam(":created", $this->created);
        $stmt->bindParam(":modified", $this->modified);

        // execute query
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;

    }

    // delete the hotel
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
