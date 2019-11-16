<?php

require_once MODEL_PATH . '/User.php';
require_once MODEL_PATH . '/UserRole.php';

class UserController extends Controller
{
    protected $connection;
    protected $userModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->userModel = new User($connection);
    }

    public function index()
    {
        try {
            Authorization($this->connection, 'usuario', 'listar');
            $page = isset($_GET['page']) ? $_GET['page'] : 1;

            $user = $this->userModel->Paginate($page);
            $userRoleModel = new UserRole($this->connection);
            $userRole = $userRoleModel->GetAll();

            $this->render('admin/user.php', [
                'user' => $user,
                'userRole' => $userRole,
            ]);
        } catch (Exception $e) {
            echo $e->getMessage() . "\n\n" . $e->getTraceAsString();
        }
    }

    public function id()
    {
        $res = new Result();
        try {
            Authorization($this->connection, 'usuario', 'modificar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $res->result = $this->userModel->GetById($body['userId']);
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
            Authorization($this->connection, 'usuario', 'crear');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $validate = $this->validateInput($body);
            if (!$validate->success) {
                throw new Exception($validate->message);
            }

            $res->result = $this->userModel->Insert($body, $_SESSION[SESS_KEY]);
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
            Authorization($this->connection, 'usuario', 'modificar');

            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $validate = $this->validateInput($body, 'update');
            if (!$validate->success) {
                throw new Exception($validate->message);
            }

            $currentDate = date('Y-m-d H:i:s');
            $this->userModel->UpdateById($body['userId'], [
                "updated_at" => $currentDate,
                "updated_user_id" => $_SESSION[SESS_KEY],

                "email" => $body['email'] ?? '',
                "avatar" => '',
                "user_name" => $body['userName'] ?? '',
                "state" => $body['state'] ?? false,

                "user_role_id" => $body['userRoleId'],
            ]);
            $res->success = true;
            $res->message = 'El registro se actualizo exitosamente';
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }
    public function updatePassword()
    {
        $res = new Result();
        try {
            Authorization($this->connection, 'usuario', 'modificar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $validate = $this->validateInput($body, 'updatePassword');
            if (!$validate->success) {
                throw new Exception($validate->message);
            }

            $currentDate = date('Y-m-d H:i:s');
            $this->userModel->UpdateById($body['userId'], [
                "updated_at" => $currentDate,
                "updated_user_id" => $_SESSION[SESS_KEY],

                "password" => sha1($body['password'] ?? ''),
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
            Authorization($this->connection, 'usuario', 'eliminar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $this->userModel->DeleteById($body['userId']);
            $res->success = true;
            $res->message = 'El registro se eliminó exitosamente';
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function validateInput($body, $type = 'create')
    {
        $res = new Result();
        $res->success = true;

        if ($type == 'create' || $type == 'update' ) {
            if (($body['email'] ?? '') == '') {
                $res->message .= 'Falta ingresar el correo electrónico | ';
                $res->success = false;
            }

            if (($body['userRoleId'] ?? '') == '') {
                $res->message .= 'Falta elegir un rol | ';
                $res->success = false;
            }

            if (($body['userName'] ?? '') == '') {
                $res->message .= 'Falta ingresar el nombre de usuario | ';
                $res->success = false;
            }
        }

        if ($type == 'update' ) {
            if (($body['userId'] ?? '') == '') {
                $res->message .= 'Falta ingresar el userId | ';
                $res->success = false;
            }
        }

        if ($type == 'create' || $type == 'updatePassword' ) {
            if (($body['password'] ?? '') == '') {
                $res->message .= 'Falta ingresar la contraseña | ';
                $res->success = false;
            }
            if (($body['passwordConfirm'] ?? '') == '') {
                $res->message .= 'Falta ingresar la confirmación contraseña | ';
                $res->success = false;
            }
            if ($body['password'] != $body['passwordConfirm']){
                $res->message .= 'Las contraseñas no coinciden | ';
                $res->success = false;
            }
        }

        return $res;
    }
}
