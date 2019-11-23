<?php


class ApiController
{
    protected $connection;
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function  index(){

    }
}