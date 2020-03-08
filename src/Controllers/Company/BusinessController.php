<?php
require_once MODEL_PATH . '/Company/Business.php';

class BusinessController extends Controller
{
    private $connection;
    private $businessModel;

    public function __construct($connection)
    {
        $this->connection = $connection;
        $this->businessModel = new Business($connection);;
    }

    public function Update()
    {
        try {
            $message = '';
            $messageType = 'info';
            $error = [];
            $business = [];

            try {
                if (isset($_POST['businessCommit'])) {
                    $business = $_POST['business'];

                    $validate = $this->BusinessValidate($business);
                    if (!$validate->success) {
                        $error = $validate->error;
                        throw new Exception($validate->message);
                    }
                    $this->businessModel->Save($business);

                    // Upload Logo
                    if (isset($_FILES['businessLogo'])) {
                        $businessLogo = $_FILES['businessLogo'];
                        $validate = $this->BusinessValidateLogo($businessLogo);
                        if (!$validate->success) {
                            $error = $validate->error;
                            throw new Exception($validate->message);
                        }

                        if (!($businessLogo['tmp_name'] ?? '') == '') {
                            $rootPath = ROOT_DIR;
                            $folderName = '/assets/files/images/';
                            if (!file_exists($rootPath . $folderName)) {
                                mkdir($rootPath . $folderName);
                            }

                            $filesName = 'L' . $business['ruc'] . '-' . $business['business_id'] . '.' . pathinfo($businessLogo['name'])['extension'];
                            if (!copy($businessLogo['tmp_name'], $rootPath . $folderName . $filesName)) {
                                throw new Exception("Error al subir el logo", 1);
                            }

                            $this->businessModel->UpdateById($business['business_id'], [
                                'logo' => $folderName . $filesName,
                            ]);
                        }
                    }

                    $message = 'El registro se actualizo exitosamente';
                    $messageType = 'success';
                }
            } catch (Exception $e) {
                $message = $e->getMessage();
                $messageType = 'error';
            }

            $business = $this->businessModel->GetByUserId($_SESSION[SESS_KEY]);
            $this->render('company/businessUpdate.php', [
                'business' => $business,
                'message' => $message,
                'error' => $error,
                'messageType' => $messageType,
            ]);
        } catch (Exception $e) {
            $this->render('500.php', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function api(){
        try {
            Authorization($this->connection, 'categoria', 'listar');
            $this->render('company/documentation.php');
        } catch (Exception $e) {
            $this->render('500.php', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function BusinessValidate($business)
    {
        $collector = new ErrorCollector();
        if (!ValidateRUC($business['ruc'] ?? '')) {
            $collector->addError('ruc', 'Número de RUC invalido');
        }
        if (trim($business['social_reason'] ?? '') == '') {
            $collector->addError('social_reason', 'No se especificó la razón social');
        }
        if (trim($business['phone'] ?? '') == '') {
            $collector->addError('phone', 'No se especificó la el teléfonos');
        }
        if (trim($business['detraction_bank_account'] ?? '') != '') {
            if (trim(strlen($business['detraction_bank_account']) != 20)) {
                $collector->addError('detraction_bank_account', 'Cuenta bancaria de detraccion CCI es inválido (20 caracteres)');
            }
        }
        return $collector->getResult();
    }

    public function BusinessValidateLogo($file)
    {
        $collector = new ErrorCollector();
        if (($file['tmp_name'] ?? '') === '') {
            $collector->addError('businessLogo', 'No se encontró ningún archivo');
        }
        if (((int) ($file['size'] ?? 0)) > 20 * 1028) {
            $collector->addError('businessLogo', 'El logo debe ser menor que 20 KB');
        }
        if (!($file['type'] === 'image/png' || $file['type'] === 'image/jpg' || $file['type'] === 'image/jpeg')) {

            $collector->addError('businessLogo', 'Logotipo El formato debe ser PNG, JPG o JPEG');
        }
        return $collector->getResult();
    }
}
