<?php


class ReportController extends Controller
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
            $this->render('company/report.php',[],'layout/company.layout.php');
        } catch (Exception $e) {
            $this->render('500.php', [
                'message' => $e->getMessage(),
            ],'layout/company.layout.php');
        }
    }

    public function sale(){
        try {
            Authorization($this->connection, 'categoria', 'listar');
            $this->render('company/reportSale.php');
        } catch (Exception $e) {
            $this->render('500.php', [
                'message' => $e->getMessage(),
            ]);
        }
    }
}