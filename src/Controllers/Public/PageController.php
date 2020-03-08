<?php

class PageController extends Controller
{
    protected $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function index()
    {
        $this->render('landingPage.php');
    }

    public function error404()
    {
        $message = $_GET['message'] ?? '';
        $this->render('404.php', [
            'message' => $message
        ],'layout/basicLayout.php');
    }
}