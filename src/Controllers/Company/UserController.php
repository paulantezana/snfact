<?php

require_once MODEL_PATH . '/User.php';
require_once MODEL_PATH . '/UserRole.php';
require_once MODEL_PATH . '/Business.php';
require_once ROOT_DIR . '/src/Helpers/TimeAuthenticator.php';
require_once ROOT_DIR . '/src/Helpers/QRCode/qrcode.class.php';

class UserController extends Controller
{
    protected $connection;
    protected $userModel;
    protected $businessModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->userModel = new User($connection);
        $this->businessModel = new Business($connection);
    }

    public function index()
    {
        try {
            Authorization($this->connection, 'usuario', 'listar');
            $userRoleModel = new UserRole($this->connection);
            $business = $this->businessModel->getByUserId($_SESSION[SESS_KEY]);
            $userRole = $userRoleModel->getAllByBusinessIdOn($business['business_id']);

            $this->render('company/user.php', [
                'userRole' => $userRole,
            ],'layout/companyLayout.php');
        } catch (Exception $e) {
            $this->render('500.php', [
                'message' => $e->getMessage(),
            ],'layout/companyLayout.php');
        }
    }

    public function table()
    {
        try {
            Authorization($this->connection, 'usuario', 'listar');
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
            $search = isset($_GET['search']) ? $_GET['search'] : '';

            $business = $this->businessModel->getByUserId($_SESSION[SESS_KEY]);
            $user = $this->userModel->paginate($page, $limit, $search, $business['business_id']);

            $this->render('company/partials/userTable.php', [
                'user' => $user,
            ]);
        } catch (Exception $e) {
            $this->render('500.php', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('/');
    }

    public function update2fa(){
        $res = new Result();
        try {
//            Authorization($this->connection, 'usuario', 'modificar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $user2faSecret = $body['user2faSecret'];
            $user2faKeyEnable = $body['user2faKeyEnable'];
            $user2faKey = $body['user2faKey'];
            $userId = $body['userId'];

            $currentDate = date('Y-m-d H:i:s');
            $timeAuthenticator = new TimeAuthenticator();
            $checkResult = $timeAuthenticator->verifyCode($user2faSecret,$user2faKey);
            if (!$checkResult) {
                throw new Exception('clave invalida');
            }

            $res = $this->userModel->updateById($userId,[
                "updated_at" => $currentDate,
                "updated_user_id" => $_SESSION[SESS_KEY],
                'fa2_secret' => $user2faSecret,
            ]);

            $res->sucess = true;
            $res->message = 'Se gardo la clave de 2 factores';
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function profile()
    {
        try{
            $timeAuthenticator = new TimeAuthenticator();
            $secret = $timeAuthenticator->createSecret();

            $host = urlencode($_SERVER['SERVER_NAME'] ?? '');
            $data = "otpauth://totp/$host?secret=$secret&issuer=" . APP_NAME;
            $qrCode = new QRcode($data, 'L');

            ob_start();
            $qrCode->displayHTML();
            $qrCodeTable = ob_get_clean();

            $user = $this->userModel->getById((int) $_SESSION[SESS_KEY]);
            $this->render('company/profile.php', [
                'user' => $user,
                'qrCodeTable' => $qrCodeTable,
                'secret' => $secret,
            ],'layout/companyLayout.php');
        }catch (Exception $e){
            $this->render('500.php', [
                'message' => $e->getMessage(),
            ],'layout/companyLayout.php');
        }
    }

    public function id()
    {
        $res = new Result();
        try {
            Authorization($this->connection, 'usuario', 'modificar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $res->result = $this->userModel->getById($body['userId']);
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
            $body['businessId'] = $this->businessModel->getByUserId($_SESSION[SESS_KEY])['business_id'];

            $res->result = $this->userModel->insert($body, $_SESSION[SESS_KEY]);
            $res->success = true;
            $res->message = 'El registro se inserto exitosamente';
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function updateProfile()
    {
        $res = new Result();
        try {
            Authorization($this->connection, 'usuario', 'modificar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $validate = $this->validateInput($body, 'updateProfile');
            if (!$validate->success) {
                throw new Exception($validate->message);
            }

            $currentDate = date('Y-m-d H:i:s');
            $this->userModel->updateById($body['userId'], [
                "updated_at" => $currentDate,
                "updated_user_id" => $_SESSION[SESS_KEY],

                "email" => $body['email'],
                "user_name" => $body['userName'],
            ]);
            $res->success = true;
            $res->message = 'El registro se actualizo exitosamente';
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
            $this->userModel->updateById($body['userId'], [
                "updated_at" => $currentDate,
                "updated_user_id" => $_SESSION[SESS_KEY],

                "email" => $body['email'],
                "user_name" => $body['userName'],
                "state" => $body['state'] ?? 0,

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
            $this->userModel->updateById($body['userId'], [
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

            $this->userModel->deleteById($body['userId']);
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

        if ($type == 'create' || $type == 'update' || $type == 'updateProfile') {
            if (($body['email'] ?? '') == '') {
                $res->message .= 'Falta ingresar el correo electrónico | ';
                $res->success = false;
            }

            if (($body['userName'] ?? '') == '') {
                $res->message .= 'Falta ingresar el nombre de usuario | ';
                $res->success = false;
            }

            if ($type != 'updateProfile'){
                if (($body['userRoleId'] ?? '') == '') {
                    $res->message .= 'Falta elegir un rol | ';
                    $res->success = false;
                }
            }
        }

        if ($type == 'update') {
            if (($body['userId'] ?? '') == '') {
                $res->message .= 'Falta ingresar el userId | ';
                $res->success = false;
            }
        }

        if ($type == 'create' || $type == 'updatePassword') {
            if (($body['password'] ?? '') == '') {
                $res->message .= 'Falta ingresar la contraseña | ';
                $res->success = false;
            }
            if (($body['passwordConfirm'] ?? '') == '') {
                $res->message .= 'Falta ingresar la confirmación contraseña | ';
                $res->success = false;
            }
            if ($body['password'] != $body['passwordConfirm']) {
                $res->message .= 'Las contraseñas no coinciden | ';
                $res->success = false;
            }
        }

        return $res;
    }
}
