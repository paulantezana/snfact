<?php

require_once MODEL_PATH . '/Company/User.php';
require_once MODEL_PATH . '/Company/AppAuthorization.php';
require_once MODEL_PATH . '/Company/Business.php';
require_once MODEL_PATH . '/Company/BusinessLocal.php';

class AuthController extends Controller
{
    protected $connection;
    protected $userModel;
    protected $businessModel;
    protected $businessLocalModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->userModel = new User($connection);
        $this->businessModel = new Business($connection);
        $this->businessLocalModel = new BusinessLocal($connection);
    }

    public function login()
    {
        try {
            if (!$_POST || !$_POST['user'] || !$_POST['password']) {
                $this->render('pages/login.php', [
                    'messageType' => 'error',
                    'message' => 'Los campos usuario y contraseña son requeridos',
                ]);
                return;
            }

            $user = $_POST['user'];
            $password = $_POST['password'];
            try {
                $loginUser = $this->userModel->login($user, $password);
                if (!$this->initApp($loginUser)) {
                    $this->logout();
                    $this->redirect('/403?message=' . urlencode('Comuniquese con el administrador'));
                    return;
                }
                $this->redirect('/dashboard');
            } catch (Exception $e) {
                $this->render('pages/login.php', [
                    'messageType' => 'error',
                    'message' => 'EL nombre usuario o contraseña es incorrecta',
                ]);
            }
        } catch (Exception $e) {
            $this->render('Public/500.php', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function register()
    {
        try {
            $message = '';
            $messageType = 'info';
            $error = [];
            $register = [];

            try {
                $this->connection->beginTransaction();
                if (isset($_POST['commit'])) {
                    $register = $_POST['register'];
                    $validate = $this->validateRegister($register);

                    if (!$validate->success) {
                        $error = $validate->error;
                        throw new Exception($validate->message);
                    }

                    $userId = $this->userModel->Insert([
                        "password" => $register['password'],
                        "email" => $register['email'],
                        "avatar" => '',
                        "userName" => $register['userName'],
                        "state" => true,
                        "userRoleId" => 1,
                    ], 1);

                    $businessId = $this->businessModel->Insert([
                        'continue_payment' => false,
                        'ruc' => $register['ruc'],
                        'social_reason' => '',
                        'commercial_reason' => '',
                        'email' => $register['email'],
                        'phone' => '',
                        'web_site' => '',
                    ], $userId);

                    $businessLocalId = $this->businessLocalModel->Insert([
                        'shortName' => 'Local principal',
                        'sunatCode' => '',
                        'locationCode' => '',
                        'address' => '',
                        'pdfInvoiceSize' => 'A4',
                        'pdfHeader' => 'Email: ' . $register['email'],
                        'description' => '',
                        'businessId' => $businessId,
                        'item' => [
                            [
                                'serie' => 'F001',
                                'documentCode' => '01',
                            ],
                            [
                                'serie' => 'B001',
                                'documentCode' => '03',
                            ],
                            [
                                'serie' => 'FP01',
                                'documentCode' => '07',
                            ],
                            [
                                'serie' => 'FP01',
                                'documentCode' => '08',
                            ],
                            [
                                'serie' => 'T001',
                                'documentCode' => '09',
                            ],
                        ]
                    ], $userId);
                }
            } catch (Exception $e) {
                $this->connection->rollBack();
                $this->render('pages/register.php', [
                    'messageType' => 'error',
                    'message' => $e->getMessage()
                ]);
                return;
            }
            $this->connection->commit();
            $this->redirect('/');
        } catch (Exception $e) {
            $this->render('Public/500.php', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    private function initApp($user)
    {
        unset($user['password']);
        unset($user['temp_key']);
        unset($user['last_update_temp_key']);
        unset($user['fa2_secret']);

        $_SESSION[SESS_KEY] = $user['user_id'];

        try {
            // Set Default Business
            $business = $this->businessModel->GetByUserId($_SESSION[SESS_KEY]);
            $businessLocals = $this->businessLocalModel->GetAllByBusinessId($business['business_id']);
            $_SESSION[SESS_CURRENT_LOCAL] = $businessLocals[0]['business_local_id'];

            // Menu
            $appAuthorizationModel = new AppAuthorization($this->connection);
            $appAuthorization = $appAuthorizationModel->GetMenu($user['user_role_id']);
            if (count($appAuthorization) < 1) {
                return false;
            }
            $_SESSION[SESS_MENU] = $appAuthorization;
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('/');
    }

    public function  forgot()
    {
        $this->render('pages/forgot.php');
    }

    public  function forgotValidate()
    {
        $key = $_GET['key'] ?? '';
        if ($key == '') {
            $this->redirect('/403');
        }

        $user = $this->userModel->GetBy('temp_key', $key);
        if (!$user->success) {
            $this->redirect('/403?message=' . $user->message);
        }

        $this->render('pages/forgotValidate.php');
    }

    public function profile()
    {
        try{
            $message = '';
            $messageType = 'info';
            $currentDate = date('Y-m-d H:i:s');

            try {
                if (isset($_POST['commitUser'])) {
                    $this->userModel->UpdateById((int) $_SESSION[SESS_KEY], [
                        "updated_at" => $currentDate,
                        "updated_user_id" => $_SESSION[SESS_KEY],

                        'email' => $_POST['userEmail'],
                        'user_name' => $_POST['userUserName'],
                    ]);
                    $message = 'El registro se actualizó exitosamente';
                    $messageType = 'success';
                } else if (isset($_POST['commitChangePassword'])) {
                    $this->userModel->UpdateById((int) $_SESSION[SESS_KEY], [
                        "updated_at" => $currentDate,
                        "updated_user_id" => $_SESSION[SESS_KEY],

                        'password' => sha1($_POST['userPassword']),
                    ]);
                    $message = 'El registro se actualizó exitosamente';
                    $messageType = 'success';
                }
            } catch (Exception $e) {
                $message = $e->getMessage();
                $messageType =  'error';
            }

            $user = $this->userModel->GetById((int) $_SESSION[SESS_KEY]);
            $this->render('company/profile.php', [
                'user' => $user,
                'message' => $message,
                'messageType' => $messageType,
            ]);
        }catch (Exception $e){
            $this->render('Public/500.php', [
                'message' => $e->getMessage(),
            ]);
        }
    }
}
