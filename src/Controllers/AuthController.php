<?php

require_once MODEL_PATH . '/User.php';
require_once MODEL_PATH . '/AppAuthorization.php';
require_once MODEL_PATH.'/Business.php';
require_once MODEL_PATH.'/BusinessLocal.php';

class AuthController extends Controller
{
    protected $connection;
    protected $userModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->userModel = new User($connection);
    }

    public function login(){
        try{
            if (!$_POST || !$_POST['user'] || !$_POST['password']){
                $this->render('pages/login.php', [
                    'messageType'=>'error',
                    'message' => 'Los campos usuario y contraseña son requeridos',
                ]);
                return;
            }

            $user = $_POST['user'];
            $password = $_POST['password'];
            try{
                $loginUser = $this->userModel->login($user,$password);
                if(!$this->initApp($loginUser)){
                    $this->logout();
                    $this->redirect('/403?message=' .urlencode('Comuniquese con el administrador'));
                    return;
                }
                $this->redirect('/dashboard');
            } catch (Exception $e){
                $this->render('pages/login.php', [
                    'messageType'=>'error',
                    'message' => 'EL nombre usuario o contraseña es incorrecta',
                ]);
            }
        } catch (Exception $e){
            echo $e->getMessage();
        }
    }

    private function initApp($user) {
        unset($user['password']);
        unset($user['temp_key']);
        unset($user['last_update_temp_key']);
        unset($user['fa2_secret']);

        $_SESSION[SESS_KEY] = $user['user_id'];
        $_SESSION[SESS_DATA] = $user;

        try{
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
            if (count($appAuthorization)<1){
                return false;
            }
            $_SESSION[SESS_MENU] = $appAuthorization;
        } catch (Exception $e){
            return false;
        }

        return true;
    }

    public function logout(){
        session_destroy();
        $this->redirect('/');
    }

    public function  forgot(){
        $this->render('pages/forgot.php');
    }

    public  function forgotValidate(){
        $key = $_GET['key'] ?? '';
        if ($key == ''){
            $this->redirect('/403');
        }

        $user = $this->userModel->GetBy('temp_key', $key);
        if (!$user->success){
            $this->redirect('/403?message=' . $user->message);
        }

        $this->render('pages/forgotValidate.php');
    }

    public function profile()
    {
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
        $this->render('admin/profile.php', [
            'user' => $user,
            'message' => $message,
            'messageType' => $messageType,
        ]);
    }
}