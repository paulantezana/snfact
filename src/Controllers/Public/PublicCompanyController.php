<?php

require_once MODEL_PATH . '/Company/User.php';
require_once MODEL_PATH . '/Company/UserRole.php';
require_once MODEL_PATH . '/Company/AppAuthorization.php';
require_once MODEL_PATH . '/Company/Business.php';
require_once MODEL_PATH . '/Company/BusinessLocal.php';
require_once ROOT_DIR . '/src/Helpers/TimeAuthenticator.php';
require_once ROOT_DIR . '/src/Services/PeruManager/PeruManager.php';

class PublicCompanyController extends Controller
{
    protected $connection;
    protected $userModel;
    protected $userRoleModel;
    protected $businessModel;
    protected $businessLocalModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->userModel = new User($connection);
        $this->userRoleModel = new UserRole($connection);
        $this->businessModel = new Business($connection);
        $this->businessLocalModel = new BusinessLocal($connection);
    }

    public function login()
    {
        try {
            if (isset($_POST['commit'])) {

                if (!isset($_POST['user']) || !isset($_POST['password'])) {
                    $this->render('Public/company/login.php', [
                        'messageType' => 'error',
                        'message' => 'Los campos usuario y contraseña son requeridos',
                    ]);
                    return;
                }

                try {
                    $user = $_POST['user'];
                    $password = $_POST['password'];

                    $loginUser = $this->userModel->login($user, $password);
                    if ($loginUser['fa2_secret_enabled']) {
                        $this->render('Public/company/posLogin.php', [
                            'userId' => $loginUser['user_id'],
                        ]);
                        return;
                    }

                    $responseApp = $this->initAppCompany($loginUser);
                    if (!$responseApp->success) {
                        session_destroy();
                        $this->render('Public/403.php', [
                            'message' => $responseApp->message,
                        ]);
                        return;
                    }

                    $this->redirect('/');
                } catch (Exception $e) {
                    $this->render('Public/company/login.php', [
                        'messageType' => 'error',
                        'message' => $e->getMessage(),
                    ]);
                }
            } else {
                $this->render('Public/company/login.php');
            }
        } catch (Exception $e) {
            $this->render('Public/500.php', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function postLogin()
    {
        try {
            $timeAuthenticator = new TimeAuthenticator();
            $faKey = $_POST['user2faKey'] ?? '';
            $userId = $_POST['userId'] ?? '';

            $userModelRes = $this->userModel->GetById($userId);
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
                $this->render('Public/company/posLogin.php', [
                    'userId' => $userId,
                ]);
            }
        } catch (Exception $e) {
            $this->render('Public/500.php', [
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
            // Set Default Business
            $businessModel = new Business($this->connection);
            $businessLocalModel = new BusinessLocal($this->connection);

            $business = $businessModel->GetByUserId($user['user_id']);
            $businessLocals = $businessLocalModel->GetAllByBusinessId($business['business_id']);
            if (count($businessLocals) === 0) {
                throw new Exception('Este usuario no está asignado a ningún local de una empresa comuníquese con el administrador.');
            }
            $_SESSION[SESS_CURRENT_LOCAL] = $businessLocals[0]['business_local_id'];

            // Menu
            $appAuthorizationModel = new AppAuthorization($this->connection);
            $appAuthorization = $appAuthorizationModel->GetMenu($user['user_role_id']);
            if (count($appAuthorization) < 1) {
                throw new Exception('No tiene autorización para acceder al sistema comuníquese con el administrador.');
            }
            $_SESSION[SESS_MENU] = $appAuthorization;

            // Group Controller
            $_SESSION[CONTROLLER_GROUP] = 'Company';

            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        return $res;
    }

    public function register()
    {
        try {
            $message = '';
            $messageType = '';
            $error = [];

            if (isset($_POST['commit'])) {
                $this->connection->beginTransaction();
                try {
                    $register = $_POST['register'];
                    $validate = $this->validateRegister($register);
                    if (!$validate->success) {
                        $error = $validate->error;
                        throw new Exception($validate->message);
                    }

                    $business = $this->businessModel->GetBy('ruc', $register['ruc']);
                    if ($business) {
                        throw new Exception('Este ruc ya esta registrado en el sistema');
                    }

                    $queryPeru = PeruManager::queryDocument($register['ruc']);
                    if(!$queryPeru->success){
                        throw new Exception($queryPeru->message);
                    }
                    $dataPeru = $queryPeru->result;

                    $userId = $this->userModel->Insert([
                        "password" => $register['password'],
                        "email" => $register['email'],
                        "avatar" => '',
                        "userName" => $register['userName'],
                        "state" => true,
                        "userRoleId" => 0,
                    ], 0);

                    $businessId = $this->businessModel->Insert([
                        'continue_payment' => false,
                        'ruc' => $register['ruc'],
                        'social_reason' => $dataPeru['socialReason'],
                        'commercial_reason' => '',
                        'email' => $register['email'],
                        'phone' => $dataPeru['telephone'],
                        'web_site' => '',
                    ], $userId);

                    $roleId = $this->userRoleModel->Insert([
                        'name'=>'Admin',
                        'businessId'=>$businessId,
                    ],$userId);

                    $this->userModel->UpdateById($userId,[
                        'user_role_id' => $roleId,
                    ]);

                    $appAuthorizationModel = new AppAuthorization($this->connection);
                    $authIds = $appAuthorizationModel->GetAllId();
                    $appAuthorizationModel->Register($authIds, $roleId);

                    $this->businessLocalModel->Insert([
                        'shortName' => 'Local principal',
                        'sunatCode' => '',
                        'locationCode' => '',
                        'address' => $dataPeru['fiscalAddress'],
                        'pdfInvoiceSize' => 'A4',
                        'pdfHeader' => 'Email: ' . $register['email'],
                        'description' => '',
                        'businessId' => $businessId,
                        'state' => 1,
                        'item' => [
                            [
                                'serie' => 'FPP1',
                                'documentCode' => '01',
                                'contingency' => 0,
                            ],
                            [
                                'serie' => 'FPP1',
                                'documentCode' => '07',
                                'contingency' => 0,
                            ],
                            [
                                'serie' => 'FPP1',
                                'documentCode' => '08',
                                'contingency' => 0,
                            ],
                            [
                                'serie' => 'BPP1',
                                'documentCode' => '03',
                                'contingency' => 0,
                            ],
                            [
                                'serie' => 'BPP1',
                                'documentCode' => '07',
                                'contingency' => 0,
                            ],
                            [
                                'serie' => 'BPP1',
                                'documentCode' => '08',
                                'contingency' => 0,
                            ],
                            [
                                'serie' => '0001',
                                'documentCode' => '01',
                                'contingency' => 1,
                            ],
                            [
                                'serie' => '0001',
                                'documentCode' => '07',
                                'contingency' => 1,
                            ],
                            [
                                'serie' => '0001',
                                'documentCode' => '08',
                                'contingency' => 1,
                            ],
                            [
                                'serie' => '0001',
                                'documentCode' => '03',
                                'contingency' => 1,
                            ],
                            [
                                'serie' => '0001',
                                'documentCode' => '07',
                                'contingency' => 1,
                            ],
                            [
                                'serie' => '0001',
                                'documentCode' => '08',
                                'contingency' => 1,
                            ],
                            [
                                'serie' => 'T001',
                                'documentCode' => '09',
                                'contingency' => 0,
                            ],
                        ]
                    ], $userId);

                    $this->connection->commit();

                    $loginUser = $this->userModel->GetById($userId);
                    $responseApp = $this->initAppCompany($loginUser);
                    if (!$responseApp->success) {
                        session_destroy();
                        $this->render('Public/403.php', [
                            'message' => $responseApp->message,
                        ]);
                        return;
                    }

                    $this->redirect('/');
                    return;
                } catch (Exception $e) {
                    $this->connection->rollBack();
                    $message = $e->getMessage();
                    $messageType = 'error';
                }
            }

            $this->render('Public/company/register.php', [
                'message' => $message,
                'error' => $error,
                'messageType' => $messageType,
            ]);
        } catch (Exception $e) {
            $this->render('Public/500.php', [
                'message' => $e->getMessage(),
            ]);
        }
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
                    $user = $this->userModel->GetBy('email', $email);
                    if (!$user) {
                        throw new Exception('Este correo electrónico no está registrado.');
                    }

                    $currentDate = date('Y-m-d H:i:s');
                    $token = sha1($currentDate . $user['user_id'] . $user['email']);
                    $this->userModel->UpdateById($user['user_id'], [
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

            $this->render('Public/company/forgot.php', [
                'message' => $resView->message,
                'messageType' => $resView->messageType,
            ]);
        } catch (Exception $e) {
            $this->render('Public/500.php', [
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
                    $user = $this->userModel->GetBy('request_key', $key);
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
                    $user['user_id'] = $_POST['userId'] ?? 0;
                    if (!($confirmPassword === $password)) {
                        throw new Exception('Las contraseñas no coinciden');
                    }
                    if (!$user['user_id']) {
                        throw new Exception('Usuario no especificado.');
                    }

                    $password = sha1($password);
                    $this->userModel->UpdateById($user['user_id'], [
                        "updated_at" => $currentDate,
                        "updated_user_id" => $user['user_id'],

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

            $this->render('Public/company/forgotValidate.php', [
                'message' => $resView->message,
                'messageType' => $resView->messageType,
                'contentType' => $resView->contentType,
                'user' => $user,
            ]);
        } catch (Exception $e) {
            $this->render('Public/500.php', [
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

    private function validateRegister($body)
    {
        $collector = new ErrorCollector();
        if (($body['email'] ?? '') == '') {
            $collector->addError('email', 'Falta ingresar el correo electrónico');
        }
        if (($body['userName'] ?? '') == '') {
            $collector->addError('userName', 'Falta ingresar el nombre de usuario');
        }
        if (($body['password'] ?? '') == '') {
            $collector->addError('password', 'Falta ingresar la contraseña');
        }
        if (($body['passwordConfirm'] ?? '') == '') {
            $collector->addError('passwordConfirm', 'Falta ingresar la confirmación contraseña');
        }
        if ($body['password'] != $body['passwordConfirm']) {
            $collector->addError('password', 'Las contraseñas no coinciden');
        }

        // Advanced Validate
        if (!ValidateRUC($body['ruc'] ?? '')) {
            $collector->addError('ruc', 'RUC Invalido');
        }

        return $collector->getResult();
    }
}
