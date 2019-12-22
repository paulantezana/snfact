<?php

require_once MODEL_PATH . '/Company/User.php';
require_once MODEL_PATH . '/Company/Business.php';
require_once MODEL_PATH . '/Company/BusinessLocal.php';

class CompanyController extends Controller
{
    private $connection;
    private $userModel;
    private $businessModel;
    private $businessLocalModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->businessModel = new Business($connection);
        $this->businessLocalModel = new BusinessLocal($connection);
        $this->userModel = new User($connection);
    }

    public function index()
    {
        try {
            $this->render('Company/dashboard.php');
        } catch (Exception $e) {
            $this->render('Public/500.php', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function fa2(){
        $this->render('/Company/fa2.php');
    }

    public function getGlobalInfo(){
        $res = new Result();
        try {
            $business = $this->businessModel->GetByUserId($_SESSION[SESS_KEY]);
            $businessLocals = $this->businessLocalModel->GetAllByBusinessId($business['business_id']);
            $user = $this->userModel->GetById($_SESSION[SESS_KEY]);

            $res->result = [
                'business' => $business,
                'businessLocals' => array_map(function ($item){
                    return [
                        'businessLocalId' => $item['business_local_id'],
                        'shortName' => $item['short_name'],
                    ];
                },$businessLocals),
                'currentLocal' => $_SESSION[SESS_CURRENT_LOCAL],
                'user' => [
                    'userId' => $user['user_id'],
                    'email' => $user['email'],
                    'avatar' => $user['avatar'],
                    'userName' => $user['user_name'],
                    'userRole' => $user['user_role_id'],
                ],
            ];
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function setCurrentLocal(){
        $res = new Result();
        try {
//            Authorization($this->connection, 'producto', 'modificar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            if (!$body['businessLocalId']){
                throw new Exception('No se especificó ningún local');
            }
            $_SESSION[SESS_CURRENT_LOCAL] = $body['businessLocalId'];

            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }
}