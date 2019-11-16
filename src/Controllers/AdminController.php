<?php


class AdminController extends Controller
{
    protected $connection;
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function  index(){
        $this->render('/admin/index.php');
    }
}