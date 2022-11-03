<?php
namespace Src\Objects;

class Hotel
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

    // create advertiser
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
            return true;
        }

        return false;

    }

    // delete the advertiser
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