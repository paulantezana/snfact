<?php

class Database
{
    private $connection;
    public function __construct()
    {
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_CASE => PDO::CASE_LOWER
        );

        $this->connection = new PDO('mysql:host=localhost;dbname=snfact', 'root', '', $options);
        // $this->connection = new PDO('mysql:host=localhost;dbname=paulpvad_snfact', 'paulpvad_yoel', 'newdata520$%yoel', $options);
        $this->connection->exec("SET CHARACTER SET UTF8");
    }
    public function getConnection()
    {
        return $this->connection;
    }
    public function closeConnection()
    {
        $this->connection = null;
    }
}
