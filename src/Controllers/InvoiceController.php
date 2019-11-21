<?php

require_once MODEL_PATH . '/Invoice.php';
require_once MODEL_PATH . '/CatCurrencyTypeCode.php';
require_once MODEL_PATH . '/CatDocumentTypeCode.php';
require_once MODEL_PATH . '/Business.php';

class InvoiceController extends Controller
{
    private $catCurrencyTypeCodeModel;
    private $catDocumentTypeCodeModel;
    private $invoiceModel;
    private $businessModel;
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->invoiceModel = new Invoice($connection);
        $this->catCurrencyTypeCodeModel = new CatCurrencyTypeCode($connection);
        $this->catDocumentTypeCodeModel = new CatDocumentTypeCode($connection);
        $this->businessModel = new Business($connection);
    }

    public function index(){
        try {
            $message = '';
            $messageType = 'info';
            $error = [];

            $this->render('admin/invoice.php',[
                'message' => $message,
                'error' => $error,
                'messageType' => $messageType,
            ]);
        } catch (Exception $e) {
            $this->render('pages/500.php',[
                'message' => $e->getMessage() . "</br>" . $e->getTraceAsString(),
            ]);
        }
    }

    public function table(){
        try {
            $message = '';
            $messageType = 'info';
            $error = [];

            $this->render('admin/invoice.php',[
                'message' => $message,
                'error' => $error,
                'messageType' => $messageType,
            ]);
        } catch (Exception $e) {
            $this->render('pages/500.php',[
                'message' => $e->getMessage() . "</br>" . $e->getTraceAsString(),
            ]);
        }
    }

    public function newFormF(){
        try {
            $message = '';
            $messageType = 'info';
            $error = [];

            $catDocumentTypeCode = $this->catDocumentTypeCodeModel->GetAll();
            $catCurrencyTypeCode = $this->catCurrencyTypeCodeModel->GetAll();

            $this->render('admin/newFormF.php',[
                'message' => $message,
                'error' => $error,
                'messageType' => $messageType,
                'catDocumentTypeCode' => $catDocumentTypeCode,
                'catCurrencyTypeCode' => $catCurrencyTypeCode,
            ]);
        } catch (Exception $e) {
            $this->render('pages/500.php',[
                'message' => $e->getMessage() . "</br>" . $e->getTraceAsString(),
            ]);
        }
    }

    public function newFormB(){
        try {
            $message = '';
            $messageType = 'info';
            $error = [];

            $this->render('admin/newFormB.php',[
                'message' => $message,
                'error' => $error,
                'messageType' => $messageType,
            ]);
        } catch (Exception $e) {
            $this->render('pages/500.php',[
                'message' => $e->getMessage() . "</br>" . $e->getTraceAsString(),
            ]);
        }
    }

    public function newFormCreditNote(){
        try {
            $message = '';
            $messageType = 'info';
            $error = [];

            $this->render('admin/newFormCreditNote.php',[
                'message' => $message,
                'error' => $error,
                'messageType' => $messageType,
            ]);
        } catch (Exception $e) {
            $this->render('pages/500.php',[
                'message' => $e->getMessage() . "</br>" . $e->getTraceAsString(),
            ]);
        }
    }

    public function newFormDebitNote(){
        try {
            $message = '';
            $messageType = 'info';
            $error = [];

            $this->render('admin/newFormCreditNote.php',[
                'message' => $message,
                'error' => $error,
                'messageType' => $messageType,
            ]);
        } catch (Exception $e) {
            $this->render('pages/500.php',[
                'message' => $e->getMessage() . "</br>" . $e->getTraceAsString(),
            ]);
        }
    }

    public function search(){
        $res = new Result();
        try{

        } catch (Exception $e){
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function resend(){
        $res = new Result();
        try{

        } catch (Exception $e){
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function sendEmail(){
        $res = new Result();
        try{

        } catch (Exception $e){
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function createF(){
        $res = new Result();
        try{

        } catch (Exception $e){
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function createB(){
        $res = new Result();
        try{

        } catch (Exception $e){
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function createCreditNote(){
        $res = new Result();
        try{

        } catch (Exception $e){
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function createDebitNote(){
        $res = new Result();
        try{

        } catch (Exception $e){
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    private function validateInvoice(){

    }

    private function invoiceItemTemplate(){

    }
}