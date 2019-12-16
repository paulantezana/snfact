<?php

require_once MODEL_PATH . '/Company/User.php';
require_once MODEL_PATH . '/Company/AppAuthorization.php';
require_once MODEL_PATH . '/Company/Business.php';
require_once MODEL_PATH . '/Company/BusinessLocal.php';

class PageController extends Controller
{
    protected $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function index()
    {
        $this->render('public/home.php');
    }

    public function login()
    {
        try {
            if (isset($_POST['commit'])) {

                if (!isset($_POST['user']) || !isset($_POST['password'])) {
                    $this->render('public/login.php', [
                        'messageType' => 'error',
                        'message' => 'Los campos usuario y contraseña son requeridos',
                    ]);
                    return;
                }

                try {
                    $user = $_POST['user'];
                    $password = $_POST['password'];
                    $userModel = new User($this->connection);

                    $loginUser = $userModel->login($user, $password);
                    if (!$this->initAppCompany($loginUser)) {
                        session_destroy();
                        $this->redirect('/403?message=' . urlencode('Comuniquese con el administrador'));
                        return;
                    }

                    $this->redirect('/');
                } catch (Exception $e) {
                    $this->render('public/login.php', [
                        'messageType' => 'error',
                        'message' => $e->getMessage(),
                    ]);
                }
            } else {
                $this->render('public/login.php');
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    private function initAppCompany($user)
    {
        unset($user['password']);
        unset($user['temp_key']);
        unset($user['last_update_temp_key']);
        unset($user['fa2_secret']);

        $_SESSION[SESS_KEY] = $user['user_id'];
        $_SESSION[SESS_DATA] = $user;

        try {
            // Set Default Business
            $businessModel = new Business($this->connection);
            $businessLocalModel = new BusinessLocal($this->connection);

            $business = $businessModel->GetByUserId($_SESSION[SESS_KEY]);
            $businessLocals = $businessLocalModel->GetAllByBusinessId($business['business_id']);

            $_SESSION[SESS_LOCALS] = $businessLocals;
            $_SESSION[SESS_CURRENT_LOCAL] = $businessLocals[0]['business_local_id'];

            // Menu
            $appAuthorizationModel = new AppAuthorization($this->connection);
            $appAuthorization = $appAuthorizationModel->GetMenu($user['user_role_id']);
            if (count($appAuthorization) < 1) {
                return false;
            }
            $_SESSION[SESS_MENU] = $appAuthorization;

            // Group Controller
            $_SESSION[CONTROLLER_GROUP] = 'Company';
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    public function managerLogin()
    {
        try {
            if (isset($_POST['commit'])) {

                if (!isset($_POST['user']) || !isset($_POST['password'])) {
                    $this->render('public/managerLogin.php', [
                        'messageType' => 'error',
                        'message' => 'Los campos usuario y contraseña son requeridos',
                    ]);
                    return;
                }

                try {
                    $user = $_POST['user'];
                    $password = $_POST['password'];
                    $userModel = new User($this->connection);

                    $loginUser = $userModel->login($user, $password);
                    if (!$this->initAppCompany($loginUser)) {
                        session_destroy();
                        $this->redirect('/403?message=' . urlencode('Comuniquese con el administrador'));
                        return;
                    }

                    $this->redirect('/');
                } catch (Exception $e) {
                    $this->render('public/managerLogin.php', [
                        'messageType' => 'error',
                        'message' => $e->getMessage(),
                    ]);
                }
            } else {
                $this->render('public/managerLogin.php');
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function register()
    {
        $this->render('public/register.php');
    }

    public function forgot()
    {
        if (isset($_SESSION[SESS_KEY])) {
            $this->redirect('/dashboard');
        }
        $this->render('public/forgot.php');
    }

    public function error404()
    {
        $message = $_GET['message'] ?? '';
        $this->render('public/404.php', [
            'message' => $message
        ]);
    }

    public function error403()
    {
        $message = $_GET['message'] ?? '';
        $this->render('public/403.php', [
            'message' => $message
        ]);
    }

    public function error500()
    {
        $message = $_GET['message'] ?? '';
        $this->render('public/500.php', [
            'message' => $message
        ]);
    }
}
