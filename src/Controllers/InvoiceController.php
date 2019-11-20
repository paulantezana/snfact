<?php


class InvoiceController extends Controller
{
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

            $this->render('admin/newFormF.php',[
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