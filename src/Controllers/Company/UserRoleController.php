<?php

require_once MODEL_PATH . '/Company/UserRole.php';
require_once MODEL_PATH . '/Company/AppAuthorization.php';

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

            $this->render('company/role.php', [
                'appAuthorization' => $appAuthorization
            ],'layout/companyLayout.php');
        } catch (Exception $e) {
            $this->render('500.php', [
                'message' => $e->getMessage(),
            ],'layout/companyLayout.php');
        }
    }

    public function  list()
    {
        try {
            Authorization($this->connection, 'rol', 'listar');
            $userRole = $this->userRoleModel->GetAll();
            $this->render('company/partials/roleList.php', [
                'userRole' => $userRole
            ]);
        } catch (Exception $e) {
            $this->render('500.php', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function id()
    {
        $res = new Result();
        try {
            Authorization($this->connection, 'rol', 'listar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);
            if (!$body) {
                echo '';
                return;
            }

            $res->result = $this->userRoleModel->GetById((int) $body['userRoleId']);
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function create()
    {
        $res = new Result();
        try {
            Authorization($this->connection, 'rol', 'crear');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $validate = $this->validateInput($body);
            if (!$validate->success) {
                echo json_encode($validate);
                return;
            }

            $res->result = $this->userRoleModel->Insert($body, $_SESSION[SESS_KEY]);
            $res->success = true;
            $res->message = 'El registro se inserto exitosamente';
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }
    public function update()
    {
        $res = new Result();
        try {
            Authorization($this->connection, 'rol', 'modificar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $validate = $this->validateInput($body, true);
            if (!$validate->success) {
                echo json_encode($validate);
                return;
            }

            $currentDate = date('Y-m-d H:i:s');
            $res->result = $this->userRoleModel->UpdateById((int) $body['userRoleId'], [
                'updated_at' => $currentDate,
                'updated_user_id' => $_SESSION[SESS_KEY],
                'name' => $body['name'],
            ]);

            $res->success = true;
            $res->message = 'El registro se actualizo exitosamente';
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }
    public function delete()
    {
        $res = new Result();
        try {
            Authorization($this->connection, 'rol', 'eliminar');

            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $res->result = $this->userRoleModel->DeleteById((int) ($body['userRoleId'] ?? 0));
            $res->success = true;
            $res->message = 'El registro se eliminó exitosamente';
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
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
