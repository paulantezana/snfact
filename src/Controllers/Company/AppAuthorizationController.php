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

    public function byUserRoleId()
    {
        $res = new Result();
        try {
            Authorization($this->connection, 'rol', 'modificar');

            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);
            if (!$body) {
                echo '';
                return;
            }

            $res->result  = $this->appAuthorizationModel->getAllByUserRoleId($body['userRoleId']);
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function save()
    {
        $res = new Result();
        try {
            Authorization($this->connection, 'rol', 'modificar');

            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $authIds = $body['authIds'] ?? [];
            $userRoleId = $body['userRoleId'] ?? 0;

            $res->result  = $this->appAuthorizationModel->save($authIds, $userRoleId, $_SESSION[SESS_KEY]);
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }
}
