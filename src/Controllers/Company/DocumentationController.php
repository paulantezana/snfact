<?php


class DocumentationController extends Controller
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
            $this->render('company/documentation.php');
        } catch (Exception $e) {
            $this->render('500.php', [
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function api()
    {
        try {
            Authorization($this->connection, 'categoria', 'listar');
            $this->render('company/documentation.php');
        } catch (Exception $e) {
            $this->render('500.php', [
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function core()
    {
        try {
            Authorization($this->connection, 'categoria', 'listar');
            $this->render('company/documentation.php');
        } catch (Exception $e) {
            $this->render('500.php', [
                'message' => $e->getMessage(),
            ]);
        }
    }
}