<?php

class QuotationController extends Controller
{
    protected $connection;
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    public function index()
    {
        try {
            $this->render('company/quotation.view.php', [

            ],'layout/company.layout.php');
        } catch (Exception $e) {
            $this->render('500.php', [
                'message' => $e->getMessage(),
            ],'layout/company.layout.php');
        }
    }
}