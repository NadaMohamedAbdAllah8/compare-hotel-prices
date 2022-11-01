<?php

class Advertiser
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
    public function read()
    {

        // select all query
        $query = "SELECT 'id', 'name', 'url', 'method'  FROM " . $this->table_name .
            "  ORDER BY created DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    // create product
    public function create()
    {

        // query to insert record
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                name=:name, url=:url, method=:method, created=:created, modified=:modified";

        // prepare query
        $stmt = $this->conn->prepare($query);

        var_dump((strip_tags($this->url)));

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->url = (strip_tags($this->url));
        // $this->url = htmlspecialchars(strip_tags($this->url));
        $this->method = htmlspecialchars(strip_tags($this->method));
        $this->created = htmlspecialchars(strip_tags(date("Y-m-d H:i:s")));
        $this->modified = htmlspecialchars(strip_tags(date("Y-m-d H:i:s")));
        var_dump($this->url);
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

}