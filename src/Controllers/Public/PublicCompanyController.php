<?php

require_once MODEL_PATH . '/User.php';
require_once MODEL_PATH . '/UserRole.php';
require_once MODEL_PATH . '/AppAuthorization.php';
require_once MODEL_PATH . '/Business.php';
require_once MODEL_PATH . '/BusinessLocal.php';
require_once ROOT_DIR . '/src/Helpers/TimeAuthenticator.php';
require_once ROOT_DIR . '/src/Services/PeruManager/PeruManager.php';
require_once ROOT_DIR . '/src/Services/SendManager/EmailManager.php';

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
                    $this->render('Company/login.view.php', [
                        'messageType' => 'error',
                        'message' => 'Los campos usuario y contraseña son requeridos',
                    ],'layout/basic.layout.php');
                    return;
                }

                try {
                    $user = $_POST['user'];
                    $password = $_POST['password'];

                    $loginUser = $this->userModel->login($user, $password);
                    if ($loginUser['fa2_secret_enabled']) {
                        $this->render('Public/company/posLogin.view.php', [
                            'userId' => $loginUser['user_id'],
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
                    $this->render('Company/login.view.php', [
                        'messageType' => 'error',
                        'message' => $e->getMessage(),
                    ],'layout/basic.layout.php');
                }
            } else {
                $this->render('Company/login.view.php',[],'layout/basic.layout.php');
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
                    $this->render('403.php', [
                        'message' => $responseApp->message,
                    ]);
                    return;
                }
                $this->redirect('/');
            } else {
                $this->render('Public/company/posLogin.view.php', [
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
            // Set Default Business
            $businessModel = new Business($this->connection);
            $businessLocalModel = new BusinessLocal($this->connection);

            $business = $businessModel->getByUserId($user['user_id']);
            $businessLocals = $businessLocalModel->getAllByBusinessId($business['business_id']);
            if (count($businessLocals) === 0) {
                throw new Exception('Este usuario no está asignado a ningún local de una empresa comuníquese con el administrador.');
            }
            $_SESSION[SESS_CURRENT_LOCAL] = $businessLocals[0]['business_local_id'];

            // Menu
            $appAuthorizationModel = new AppAuthorization($this->connection);
            $appAuthorization = $appAuthorizationModel->getMenu($user['user_role_id']);
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

                    $business = $this->businessModel->getBy('ruc', $register['ruc']);
                    if ($business) {
                        throw new Exception('Este ruc ya esta registrado en el sistema');
                    }

                    $queryPeru = PeruManager::queryDocument($register['ruc']);
                    if(!$queryPeru->success){
                        throw new Exception($queryPeru->message);
                    }
                    $dataPeru = $queryPeru->result;

                    $userId = $this->userModel->insert([
                        "password" => $register['password'],
                        "email" => $register['email'],
                        "avatar" => '',
                        "userName" => $register['userName'],
                        "state" => true,
                        "userRoleId" => 0,
                    ], 0);

                    $businessId = $this->businessModel->insert([
                        'continue_payment' => false,
                        'ruc' => $register['ruc'],
                        'social_reason' => $dataPeru['socialReason'],
                        'commercial_reason' => $dataPeru['socialReason'],
                        'email' => $register['email'],
                        'phone' => $dataPeru['telephone'],
                        'web_site' => '',
                        'environment' => false,
                        'state' => true,
                    ], $userId);

                    $roleId = $this->userRoleModel->insert([
                        'name' => 'Admin',
                        'businessId' => $businessId,
                        'state' => true,
                    ], $userId);

                    $this->userModel->updateById($userId,[
                        'user_role_id' => $roleId,
                    ]);

                    $appAuthorizationModel = new AppAuthorization($this->connection);
                    $authIds = $appAuthorizationModel->getAllId();
                    $appAuthorizationModel->register($authIds, $roleId);

                    $this->businessLocalModel->insert([
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

                    $loginUser = $this->userModel->getById($userId);
                    $responseApp = $this->initAppCompany($loginUser);
                    if (!$responseApp->success) {
                        session_destroy();
                        $this->render('403.php', [
                            'message' => $responseApp->message,
                        ]);
                        return;
                    }

                    $urlApp = HOST . URL_PATH . '/publicCompany/login';
                    $resEmail = EmailManager::send(APP_EMAIL, $register['email'], '¡🚀 Bienvenido a '.APP_NAME.' !',
                      '<div>
                                    <h1>' . $dataPeru['socialReason'] . ', bienvenido(a) a ' . APP_NAME . '. Acelera tu negocio</h1>
                                    <p>Facturación electrónica</p>
                                    <a href="' . $urlApp . '">Ingresar al sistema</a>
                                </div>');
                    if(!$resEmail->success){
                      throw new Exception($resEmail->message);
                    }

                    $this->redirect('/');
                    return;
                } catch (Exception $e) {
                    $this->connection->rollBack();
                    $message = $e->getMessage();
                    $messageType = 'error';
                }
            }

            $this->render('company/register.view.php', [
                'message' => $message,
                'error' => $error,
                'messageType' => $messageType,
            ],'layout/basic.layout.php');
        } catch (Exception $e) {
            $this->render('500.php', [
                'message' => $e->getMessage(),
            ],'layout/basic.layout.php');
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
                    $user = $this->userModel->getBy('email', $email);
                    if (!$user) {
                        throw new Exception('Este correo electrónico no está registrado.');
                    }

                    $currentDate = date('Y-m-d H:i:s');
                    $token = sha1($currentDate . $user['user_id'] . $user['email']);
                    $this->userModel->updateById($user['user_id'], [
                        'request_key' => $token,
                        'request_key_date' => $currentDate,
                    ]);

                    $urlForgot = HOST . URL_PATH . '/publicCompany/forgotValidate?key=' .$token;
                    $resEmail = EmailManager::send(APP_EMAIL, $user['email'], 'Recupera tu Contraseña',
                        '<p>Recientemente se solicitó un cambio de contraseña en tu cuenta. Si no fuiste tú, ignora este mensaje y sigue disfrutando de la experiencia de ' . APP_NAME . '.</p>
                                 <a href="' . $urlForgot . '" target="_blanck">Cambiar contraseña</a>'
                    );
                    if(!$resEmail->success){
                        throw new Exception($resEmail->message);
                    }

                    $resView->message = 'El correo electrónico de confirmación de restablecimiento de contraseña se envió a su correo electrónico.';
                    $resView->messageType = 'success';
                } catch (Exception $exception) {
                    $resView->message = $exception->getMessage();
                    $resView->messageType = 'error';
                }
            }

            $this->render('company/forgot.view.php', [
                'message' => $resView->message,
                'messageType' => $resView->messageType,
            ],'layout/basic.layout.php');
        } catch (Exception $e) {
            $this->render('500.php', [
                'message' => $e->getMessage(),
            ],'layout/basic.layout.php');
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
                    $user['user_id'] = $_POST['userId'];
                    if (!($confirmPassword === $password)) {
                        throw new Exception('Las contraseñas no coinciden');
                    }
                    if (!$user['user_id']) {
                        throw new Exception('Usuario no especificado.');
                    }

                    $password = sha1($password);
                    $this->userModel->updateById($user['user_id'], [
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

            $this->render('company/forgotValidate.view.php', [
                'message' => $resView->message,
                'messageType' => $resView->messageType,
                'contentType' => $resView->contentType,
                'user' => $user,
            ],'layout/basic.layout.php');
        } catch (Exception $e) {
            $this->render('500.php', [
                'message' => $e->getMessage(),
            ],'layout/basic.layout.php');
        }
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
