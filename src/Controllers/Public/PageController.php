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
        $this->render('Public/index.php');
    }

    public function error404()
    {
        $message = $_GET['message'] ?? '';
        $this->render('Public/404.php', [
            'message' => $message
        ]);
    }

    public function error403()
    {
        $message = $_GET['message'] ?? '';
        $this->render('Public/403.php', [
            'message' => $message
        ]);
    }

    public function error500()
    {
        $message = $_GET['message'] ?? '';
        $this->render('Public/500.php', [
            'message' => $message
        ]);
    }
}
