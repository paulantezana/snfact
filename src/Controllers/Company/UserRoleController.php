<?php

require_once MODEL_PATH . '/UserRole.php';
require_once MODEL_PATH . '/Business.php';
require_once MODEL_PATH . '/AppAuthorization.php';

class UserRoleController extends Controller
{
    private $connection;
    private $userRoleModel;
    private $businessModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->userRoleModel = new UserRole($connection);
        $this->businessModel = new Business($connection);
    }

    public function index()
    {
        try {
            Authorization($this->connection, 'rol', 'listar');
            $appAuthorizationModel = new AppAuthorization($this->connection);
            $appAuthorization = $appAuthorizationModel->getAll();

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

            $business = $this->businessModel->getByUserId($_SESSION[SESS_KEY]);
            $userRole = $this->userRoleModel->getAllByBusinessId($business['business_id']);
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

            $res->result = $this->userRoleModel->getById((int) $body['userRoleId']);
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
            $body['businessId'] = $this->businessModel->getByUserId($_SESSION[SESS_KEY])['business_id'];

            $res->result = $this->userRoleModel->insert($body, $_SESSION[SESS_KEY]);
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
            $res->result = $this->userRoleModel->updateById((int) $body['userRoleId'], [
                'updated_at' => $currentDate,
                'updated_user_id' => $_SESSION[SESS_KEY],
                'name' => $body['name'],
                'state' => $body['state'],
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

            $res->result = $this->userRoleModel->deleteById((int) ($body['userRoleId'] ?? 0));
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
