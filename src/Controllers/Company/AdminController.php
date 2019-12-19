<?php

require_once MODEL_PATH . '/Company/User.php';
require_once MODEL_PATH . '/Company/Business.php';
require_once MODEL_PATH . '/Company/BusinessLocal.php';

class AdminController extends Controller
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

    public function  index()
    {
        $this->render('/Company/index.php');
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
}
