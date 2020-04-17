<?php

require_once MODEL_PATH . '/User.php';
require_once MODEL_PATH . '/AppAuthorization.php';
require_once ROOT_DIR . '/src/Helpers/TimeAuthenticator.php';

class PublicManagerController extends Controller
{
    protected $connection;
    protected $userModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->userModel = new User($connection);
    }

    public function login()
    {
        try {
            if (isset($_POST['commit'])) {

                if (!isset($_POST['user']) || !isset($_POST['password'])) {
                    $this->render('manager/login.php', [
                        'messageType' => 'error',
                        'message' => 'Los campos usuario y contraseña son requeridos',
                    ],'layout/basic.layout.php');
                    return;
                }

                try {
                    $user = $_POST['user'];
                    $password = $_POST['password'];
                    $loginUser = $this->userModel->login($user, $password, 1);
                    if ($loginUser['fa2_secret_enabled']) {
                        $this->render('manager/posLogin.php', [
                            'userId' => $loginUser['mng_user_id'],
                        ],'layout/basic.layout.php');
                        return;
                    }

                    $responseApp = $this->initAppCompany($loginUser);
                    if (!$responseApp->success) {
                        session_destroy();
                        $this->render('403.php', [
                            'message' => $responseApp->message,
                        ],'layout/basic.layout.php');
                        return;
                    }

                    $this->redirect('/');
                } catch (Exception $e) {
                    $this->render('manager/login.php', [
                        'messageType' => 'error',
                        'message' => $e->getMessage(),
                    ],'layout/basic.layout.php');
                }
            } else {
                $this->render('manager/login.php',[],'layout/basic.layout.php');
            }
        } catch (Exception $e) {
            $this->render('500.php', [
                'message' => $e->getMessage(),
            ],'layout/basic.layout.php');
        }
    }

    public function postLogin()
    {
        try {
            $timeAuthenticator = new TimeAuthenticator();
            $faKey = $_POST['user2faKey'] ?? '';
            $userId = $_POST['userId'] ?? '';

            $userModelRes = $this->userModel->getById($userId);
            $checkResult = $timeAuthenticator->verifyCode($userModelRes['fa2_secret'] ?? '', $faKey);
            if ($checkResult) {
                $responseApp = $this->initAppCompany($checkResult);
                if (!$responseApp->success) {
                    session_destroy();
                    $this->render('Public/403.php', [
                        'message' => $responseApp->message,
                    ]);
                    return;
                }
                $this->redirect('/');
            } else {
                $this->render('manager/posLogin.php', [
                    'userId' => $userId,
                ]);
            }
        } catch (Exception $e) {
            $this->render('500.php', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    private function initAppCompany($user)
    {
        unset($user['password']);
        unset($user['request_key']);
        unset($user['request_key_date']);
        unset($user['fa2_secret']);

        $_SESSION[SESS_KEY] = $user['user_id'];
        $res = new Result();
        try {
            // Menu
            // $appAuthorizationModel = new AppAuthorization($this->connection);
            // $appAuthorization = $appAuthorizationModel->GetMenu($user['user_role_id']);
            // if (count($appAuthorization) < 1) {
            //     throw new Exception('No tiene autorización para acceder al sistema comuníquese con el administrador.');
            // }
            // $_SESSION[SESS_MENU] = $appAuthorization;

            // Group Controller
            $_SESSION[CONTROLLER_GROUP] = 'Manager';

            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        return $res;
    }

    public function forgot()
    {
        if (isset($_SESSION[SESS_KEY])) {
            $this->redirect('/');
        }

        try {
            $resView = new Result();
            $resView->messageType = '';

            if (isset($_POST['commit'])) {
                try {
                    $email = $_POST['email'] ?? '';
                    if (($email) == '') {
                        throw new Exception('Falta ingresar el correo');
                    }
                    $user = $this->userModel->getBy('email', $email);
                    if (!$user) {
                        throw new Exception('Este correo electrónico no está registrado.');
                    }

                    $currentDate = date('Y-m-d H:i:s');
                    $token = sha1($currentDate . $user['mng_user_id'] . $user['email']);
                    $this->userModel->updateById($user['mng_user_id'], [
                        'request_key' => $token,
                        'request_key_date' => $currentDate,
                    ]);

                    $this->SendEmail([
                        'to' => $user['email'],
                        'token' => $token,
                    ]);

                    $resView->message = 'El correo electrónico de confirmación de restablecimiento de contraseña se envió a su correo electrónico.';
                    $resView->messageType = 'success';
                } catch (Exception $exception) {
                    $resView->message = $exception->getMessage();
                    $resView->messageType = 'error';
                }
            }

            $this->render('manager/forgot.php', [
                'message' => $resView->message,
                'messageType' => $resView->messageType,
            ]);
        } catch (Exception $e) {
            $this->render('500.php', [
                'message' => $e->getMessage(),
            ]); 
        }
    }

    public function forgotValidate()
    {
        if (isset($_SESSION[SESS_KEY])) {
            $this->redirect('/');
        }

        try {
            $resView = new Result();
            $resView->messageType = '';
            $resView->contentType = 'validateToken';

            $user = [];
            $currentDate = date('Y-m-d H:i:s');

            // change password
            if (isset($_GET['key'])) {
                $resView->contentType = 'validateToken';
                $key = $_GET['key'];
                try {
                    $user = $this->userModel->getBy('request_key', $key);
                    if (!$user) {
                        throw new Exception('Token invalido o expirado');
                    }

                    $diff = strtotime($currentDate) - strtotime($user['request_key_date']);
                    if (($diff / 60) > 120) {
                        throw new Exception('Token expirado');
                    }

                    $resView->message = 'Token valido cambie su contraseña';
                    $resView->messageType = 'success';
                } catch (Exception $e) {
                    $resView->message = $e->getMessage();
                    $resView->messageType = 'error';
                }
            } else if (isset($_POST['commit'])) {
                $resView->contentType = 'changePassword';
                try {
                    $password = $_POST['password'];
                    $confirmPassword = $_POST['confirmPassword'];
                    $user['mng_user_id'] = $_POST['userId'] ?? 0;
                    if (!($confirmPassword === $password)) {
                        throw new Exception('Las contraseñas no coinciden');
                    }
                    if (!$user['mng_user_id']) {
                        throw new Exception('Usuario no especificado.');
                    }

                    $password = sha1($password);
                    $this->userModel->updateById($user['mng_user_id'], [
                        "updated_at" => $currentDate,
                        "updated_mng_user_id" => $user['mng_user_id'],

                        'password' => $password,
                        'request_key' => '',
                    ]);

                    $resView->message = 'Cambio de contraseña exitosa';
                    $resView->messageType = 'success';
                } catch (Exception $e) {
                    $resView->message = $e->getMessage();
                    $resView->messageType = 'error';
                }
            }

            $this->render('manager/forgotValidate.php', [
                'message' => $resView->message,
                'messageType' => $resView->messageType,
                'contentType' => $resView->contentType,
                'user' => $user,
            ]);
        } catch (Exception $e) {
            $this->render('500.php', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    private function SendEmail($mailContent)
    {
        $currentUrl = HOST . URL_PATH . '/forgot/validate';
        $tokenUrl = $currentUrl . '?token=' . $mailContent['token'];

        $to = $mailContent['to'];
        $from = 'paul.antezana.2@gmail.com';
        $subject = 'Recupera tu Contraseña';
        $message = "
        <div style='background: #F9FAFC;padding: 5rem 0; text-align: center;'>
            <div style='max-width:590px!important; width:590px; background: white;padding: 2rem;margin: auto;'>
                <img src='https://www.skynetcusco.com/wp-content/uploads/2016/11/logosky2017.png' alt='logo' width='42px'>
                <h2>¿Olvidaste tu contraseña?</h2>
                <p>Recientemente se solicitó un cambio de contraseña en tu cuenta. Si no fuiste tú, Ignora este mensaje y sigue realizando sus ventas en Skynet</p>
                <p>Si deseas hacer el cambio, haz click en el siguiente botón.</p>
                <a href='{$tokenUrl}' target='_blank'>
                    <div style=\"background: #007BFF; color: white; display: inline-block; padding: .7rem; text-decoration: none; border-radius: 4px;\">Cambiar contraseña</div>
                </a>
                <div>
                    <a href='{$tokenUrl}' target='_blank'>{$tokenUrl}</a>            
                </div>
            </div>
            <p style='margin-top: 3rem;'>© 2019 Skynet</p>
        </div>";

        $header = 'MIME-Version: 1.0' . PHP_EOL;
        $header .= 'Content-type: text/html; charset=UTF-8' . PHP_EOL;

        $header .= 'To: s <' . $to . '>' . PHP_EOL;
        $header .= 'From: SnFact <' . $from . '>' . PHP_EOL;
        mail($to, $subject, $message, $header);
    }
}
