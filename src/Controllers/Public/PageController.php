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
        $this->render('public/index.php');
    }

    public function error404()
    {
        $message = $_GET['message'] ?? '';
        $this->render('public/404.php', [
            'message' => $message
        ]);
    }

    public function error403()
    {
        $message = $_GET['message'] ?? '';
        $this->render('public/403.php', [
            'message' => $message
        ]);
    }

    public function error500()
    {
        $message = $_GET['message'] ?? '';
        $this->render('public/500.php', [
            'message' => $message
        ]);
    }
}
