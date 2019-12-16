<?php


class VoidedController extends Controller
{
    protected $connection;
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function index()
    {
        try {
            Authorization($this->connection, 'categoria', 'listar');
            $this->render('company/voided.php');
        } catch (Exception $e) {
            $this->render('Public/500.php', [
                'message' => $e->getMessage(),
            ]);
        }
    }
}