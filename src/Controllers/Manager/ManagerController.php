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
            $this->render('Manager/dashboard.php');
        } catch (Exception $e) {
            $this->render('Public/500.php', [
                'message' => $e->getMessage(),
            ]);
        }
    }
}