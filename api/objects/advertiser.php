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

}