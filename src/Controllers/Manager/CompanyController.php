<?php

require_once MODEL_PATH . '/User.php';
require_once MODEL_PATH . '/UserRole.php';
require_once MODEL_PATH . '/AppAuthorization.php';
require_once MODEL_PATH . '/Business.php';
require_once MODEL_PATH . '/BusinessLocal.php';

require_once ROOT_DIR . '/src/Services/PeruManager/PeruManager.php';

class CompanyController extends Controller
{
    private $connection;
    private $userModel;
    private $userRoleModel;
    private $businessModel;
    private $businessLocalModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->userModel = new User($connection);
        $this->userRoleModel = new UserRole($connection);
        $this->businessModel = new Business($connection);
        $this->businessLocalModel = new BusinessLocal($connection);
    }

    public function index()
    {
        try {
            // Authorization($this->connection, 'company', 'listar');
            $this->render('manager/company.php',[],'layout/managerLayout.php');
        } catch (Exception $e) {
            $this->render('500.php', [
                'message' => $e->getMessage(),
            ],'layout/managerLayout.php');
        }
    }

    public function table()
    {
        try {
            // Authorization($this->connection, 'company', 'listar');
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
            $search = isset($_GET['search']) ? $_GET['search'] : '';

            $company = $this->businessModel->paginate($page, $limit, $search);

            $this->render('manager/partials/companyTable.php', [
                'company' => $company,
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
            // Authorization($this->connection, 'company', 'modificar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $res->result = $this->businessModel->getById($body['companyId']);
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function create()
    {
        $res = new Result();
        $this->connection->beginTransaction();
        try {
            // Authorization($this->connection, 'company', 'crear');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            // $validate = $this->validateInput($body);
            // if (!$validate->success) {
            //     throw new Exception($validate->message);
            // }

            $business = $this->businessModel->getBy('ruc', $body['ruc']);
            if ($business) {
                throw new Exception('Este ruc ya esta registrado en el sistema');
            }

            $queryPeru = PeruManager::queryDocument($body['ruc']);
            if(!$queryPeru->success){
                throw new Exception($queryPeru->message);
            }
            $dataPeru = $queryPeru->result;

            $userId = $this->userModel->insert([
                "password" => $body['password'],
                "email" => $body['email'],
                "avatar" => '',
                "userName" => $body['userName'],
                "state" => true,
                "userRoleId" => 0,
            ], 0);

            $businessId = $this->businessModel->insert([
                'continue_payment' => false,
                'ruc' => $body['ruc'],
                'social_reason' => $dataPeru['socialReason'],
                'commercial_reason' => $body['commercialReason'],
                'email' => $body['email'],
                'phone' => $dataPeru['telephone'] != '' ? $dataPeru['telephone'] : $body['phone'],
                'web_site' => $body['webSite'],
                'environment' => $body['environment'],
                'state' => $body['state'],
            ], $userId);

            $roleId = $this->userRoleModel->insert([
                'name'=>'Admin',
                'businessId'=>$businessId,
            ],$userId);

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
                'pdfHeader' => 'Email: ' . $body['email'],
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

            $res->success = true;
            $res->message = 'El registro se inserto exitosamente';
        } catch (Exception $e) {
            $this->connection->rollBack();
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function update()
    {
        $res = new Result();
        try {
            // Authorization($this->connection, 'company', 'modificar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            // $validate = $this->validateInput($body);
            // if (!$validate->success) {
            //     throw new Exception($validate->message);
            // }

            // $business = $this->businessModel->GetBy('ruc', $body['ruc']);
            // if ($business) {
            //     throw new Exception('Este ruc ya esta registrado en el sistema');
            // }

            $queryPeru = PeruManager::queryDocument($body['ruc']);
            if(!$queryPeru->success){
                throw new Exception($queryPeru->message);
            }
            $dataPeru = $queryPeru->result;

            $currentDate = date('Y-m-d H:i:s');
            $this->businessModel->updateById($body['companyId'], [
                'updated_at' => $currentDate,
                'updated_user_id' => $_SESSION[SESS_KEY],

                'social_reason' => $dataPeru['socialReason'],
                'email' => $body['email'],
                'web_site' => $body['webSite'],
                'state' => $body['state'],
                'environment' => $body['environment'],
                'ruc' => $body['ruc'],
                'phone' => $dataPeru['telephone'] != '' ? $dataPeru['telephone'] : $body['phone'],
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
            Authorization($this->connection, 'company', 'eliminar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $this->businessModel->deleteById($body['companyId']);
            $res->success = true;
            $res->message = 'El registro se eliminó exitosamente';
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function validateInput($body)
    {
        $res = new Result();
        $res->success = true;

        if (($body['name']) == '') {
            $res->message .= 'Falta ingresar el nombre de la company';
            $res->success = false;
        }

        return $res;
    }
}