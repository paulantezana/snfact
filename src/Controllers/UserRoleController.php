<?php

require_once MODEL_PATH . '/UserRole.php';
require_once MODEL_PATH . '/AppAuthorization.php';

class UserRoleController extends Controller
{
    protected $connection;
    protected $userRoleModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->userRoleModel = new UserRole($connection);
    }

    public function index()
    {
        try {
            Authorization($this->connection, 'rol', 'listar');

            $appAuthorizationModel = new AppAuthorization($this->connection);
            $appAuthorization = $appAuthorizationModel->GetAll();

            $this->render('admin/role.php', [
                'appAuthorization' => $appAuthorization
            ]);
        } catch (Exception $e) {
            $this->render('pages/500', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function  list()
    {
        try {
            Authorization($this->connection, 'rol', 'listar');

            $userRole = $this->userRoleModel->GetAll();
            $this->render('admin/partials/roleList.php', [
                'userRole' => $userRole
            ]);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function id()
    {
        Authorization($this->connection, 'rol', 'listar');

        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);
        if (!$body) {
            echo '';
            return;
        }

        $userRole = $this->userRoleModel->GetById((int) $body['userRoleId']);
        echo json_encode($userRole);
    }

    public function create()
    {
        Authorization($this->connection, 'rol', 'crear');

        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);

        $validate = $this->validateInput($body);
        if (!$validate->success) {
            echo json_encode($validate);
            return;
        }

        $res = $this->userRoleModel->Insert($body, $_SESSION[SESS_KEY]);
        echo json_encode($res);
    }
    public function update()
    {
        Authorization($this->connection, 'rol', 'modificar');

        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);

        $validate = $this->validateInput($body, true);
        if (!$validate->success) {
            echo json_encode($validate);
            return;
        }

        $res = $this->userRoleModel->UpdateById((int) $body['userRoleId'], [
            'name' => $body['name'] ?? '',
        ]);
        echo json_encode($res);
    }
    public function delete()
    {
        Authorization($this->connection, 'rol', 'eliminar');

        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);

        $res = $this->userRoleModel->DeleteById((int) ($body['userRoleId'] ?? 0));
        echo json_encode($res);
    }

    public function validateInput($body, $update = false)
    {
        $res = new Result();
        $res->success = true;

        if ($update) {
            if (($body['userRoleId'] ?? '') == '') {
                $res->message .= 'Falta ingresar el userRoleId | ';
                $res->success = false;
            }
        }

        if (($body['name'] ?? '') == '') {
            $res->message .= 'Falta ingresar el nombre';
            $res->success = false;
        }

        return $res;
    }
}
