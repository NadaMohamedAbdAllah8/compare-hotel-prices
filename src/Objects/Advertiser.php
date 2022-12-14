<?php
namespace App\Objects;

use App\Objects\AdvertiserInterface;
use App\Objects\DatabaseModel;
use PDO;

class Advertiser implements AdvertiserInterface, DatabaseModel
{

    // database connection and table name
    private $conn;
    private $table_name = "advertisers";

    // object properties
    public $id;
    public $name;
    public $url;
    public $method;
    public $created;
    public $modified;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // read advertisers
    // return advertisers array, or false
    public function read()
    {
        // select all query
        $query = "SELECT id, name, url, method  FROM " . $this->table_name .
            "  ORDER BY created DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        if ($stmt->execute()) {

            //return $stmt; {
            $num = $stmt->rowCount();

            // check if more than 0 record found
            if ($num > 0) {

                // advertisers array
                $advertisers_arr = array();

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

                    array_push($advertisers_arr, $advertiser_item);
                }

                return $advertisers_arr;

            }
        }

        return false;
    }

// create advertiser
    public function create()
    {

        // query to insert record
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                name=:name, url=:url, method=:method, created=:created, modified=:modified";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->url = (strip_tags($this->url));
        // $this->url = htmlspecialchars(strip_tags($this->url));
        $this->method = htmlspecialchars(strip_tags($this->method));
        $this->created = htmlspecialchars(strip_tags(date("Y-m-d H:i:s")));
        $this->modified = htmlspecialchars(strip_tags(date("Y-m-d H:i:s")));

        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(':url', $this->url);
        $stmt->bindParam(":method", $this->method);
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

    // readOne advertisers
    public function readOne()
    {
        // select all query
        $query = "SELECT id, name, url, method  FROM " . $this->table_name .
            "  where id = ?";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind id of record to delete
        $stmt->bindParam(1, $this->id);

        // execute query
        if ($stmt->execute()) {

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                extract($row);
                return $row;

            } else {
                // echo 'FETCH_ASSOC is false                          ';
                return false;

            }

        }

        return false;

    }

// get data from API
//     public function getDataFromAPI()
//     {
// $Advertiser1Data=new Advertiser1Data()
//     }
}
