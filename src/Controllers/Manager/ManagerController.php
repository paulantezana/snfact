<?php


class ManagerController extends Controller
{
    protected $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function index()
    {
        try {
            $this->render('Manager/dashboard.php',[],'layout/managerLayout.php');
        } catch (Exception $e) {
            $this->render('500.php', [
                'message' => $e->getMessage(),
            ],'layout/managerLayout.php');
        }
    }
    public function error404()
    {
        $message = $_GET['message'] ?? '';
        $this->render('404.php', [
            'message' => $message
        ],'layout/managerLayout.php');
    }
}