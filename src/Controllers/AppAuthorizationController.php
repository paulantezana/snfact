<?php

require_once MODEL_PATH . '/AppAuthorization.php';

class AppAuthorizationController extends  Controller
{
    protected $connection;
    protected $appAuthorizationModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->appAuthorizationModel = new AppAuthorization($connection);
    }

    public function byUserRoleId(){
        Authorization($this->connection,'rol','modificar');

        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);
        if (!$body){
            echo '';
            return;
        }

        $appAuthorization = $this->appAuthorizationModel->GetAllByUserRoleId($body['userRoleId']);
        echo json_encode($appAuthorization);
    }

    public function save(){
        Authorization($this->connection,'rol','modificar');

        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);

        $authIds = $body['authIds'] ?? [];
        $userRoleId = $body['userRoleId'] ?? 0;

        $res = $this->appAuthorizationModel->Save($authIds, $userRoleId, $_SESSION[SESS_KEY]);
        echo  json_encode($res);
    }
}