<?php

class PageController extends Controller
{
    protected $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function login()
    {
        if (isset($_SESSION[SESS_KEY])){
            $this->redirect('/admin');
        }
        $this->render('pages/login.php');
    }

    public function error404(){
        $message = $_GET['message'] ?? '';
        $this->render('pages/404.php',[
            'message' => $message
        ]);
    }

    public function error403(){
        $message = $_GET['message'] ?? '';
        $this->render('pages/403.php',[
            'message' => $message
        ]);
    }

    public function error500(){
        $message = $_GET['message'] ?? '';
        $this->render('pages/500.php',[
            'message' => $message
        ]);
    }

    public function dashboard()
    {
        $this->render('admin/dashboard.php');
    }
}