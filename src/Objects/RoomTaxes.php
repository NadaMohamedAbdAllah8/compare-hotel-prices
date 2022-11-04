<?php
namespace App\Objects;

use App\Objects\DatabaseModel;

class RoomTaxes implements DatabaseModel
{

    // database connection and table name
    private $conn;
    private $table_name = "hotels";

    // object properties
    public $id;
    public $room_id;
    public $currency;
    public $type;
    public $amount;
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
        $query = "SELECT id, room_id, currency, type, amount  FROM " . $this->table_name .
            "  ORDER BY created DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    // create room taxes
    public function create()
    {

        // query to insert record
        $query = "INSERT INTO
                " . $this->table_currency . "
            SET
                currency=:currency, room_id=:room_id, type=:type,amount=:amount,  created=:created, modified=:modified";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->currency = htmlspecialchars(strip_tags($this->currency));
        $this->type = htmlspecialchars(strip_tags($this->type));
        $this->room_id = htmlspecialchars(strip_tags($this->room_id));
        $this->amount = htmlspecialchars(strip_tags($this->amount));
        $this->created = htmlspecialchars(strip_tags(date("Y-m-d H:i:s")));
        $this->modified = htmlspecialchars(strip_tags(date("Y-m-d H:i:s")));

        // bind values
        $stmt->bindParam(":currency", $this->currency);
        $stmt->bindParam(":type", $this->type);
        $stmt->bindParam(':room_id', $this->room_id);
        $stmt->bindParam(":amount", $this->type);
        $stmt->bindParam(":created", $this->created);
        $stmt->bindParam(":modified", $this->modified);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;

    }

    // delete the room taxes
    public function delete()
    {

        // delete query
        $query = "DELETE FROM " . $this->table_currency . " WHERE id = ?";

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