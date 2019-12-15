<?php

require_once MODEL_PATH . '/Company/Customer.php';
require_once MODEL_PATH . '/Company/Business.php';
require_once MODEL_PATH . '/Catalogue/CatIdentityDocumentTypeCode.php';

class CustomerController extends Controller
{
    protected $connection;
    protected $customerModel;
    protected $businessModel;
    protected $catIdentityDocumentTypeCodeModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->customerModel = new Customer($connection);
        $this->businessModel = new Business($connection);
        $this->catIdentityDocumentTypeCodeModel = new CatIdentityDocumentTypeCode($connection);
    }

    public function index()
    {
        try {
            Authorization($this->connection, 'cliente', 'listar');
            $catIdentityDocumentTypeCode = $this->catIdentityDocumentTypeCodeModel->GetAll();

            $this->render('company/customer.php', [
                'catIdentityDocumentTypeCode' => $catIdentityDocumentTypeCode,
            ]);
        } catch (Exception $e) {
            echo $e->getMessage() . "\n\n" . $e->getTraceAsString();
        }
    }

    public function table()
    {
        try {
            Authorization($this->connection, 'cliente', 'listar');
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
            $search = isset($_GET['search']) ? $_GET['search'] : '';

            $business = $this->businessModel->GetByUserId($_SESSION[SESS_KEY]);
            $customer = $this->customerModel->Paginate($page, $limit, $search, $business['business_id']);

            $this->render('company/partials/customerTable.php', [
                'customer' => $customer,
            ]);
        } catch (Exception $e) {
            echo $e->getMessage() . "\n\n" . $e->getTraceAsString();
        }
    }

    public function id()
    {
        $res = new Result();
        try {
            Authorization($this->connection, 'cliente', 'modificar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $res->result = $this->customerModel->GetById($body['customerId']);
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
            Authorization($this->connection, 'cliente', 'crear');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $validate = $this->validateInput($body);
            if (!$validate->success) {
                throw new Exception($validate->message);
            }

            $body['businessId'] = $this->businessModel->GetByUserId($_SESSION[SESS_KEY])['business_id'];

            $res->result = $this->customerModel->Insert($body, $_SESSION[SESS_KEY]);
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
            Authorization($this->connection, 'cliente', 'modificar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $validate = $this->validateInput($body);
            if (!$validate->success) {
                throw new Exception($validate->message);
            }

            $currentDate = date('Y-m-d H:i:s');
            $this->customerModel->UpdateById($body['customerId'], [
                'updated_at' => $currentDate,
                'updated_user_id' => $_SESSION[SESS_KEY],

                'document_number' => $body['documentNumber'],
                'state' => $body['state'],
                'identity_document_code' => $body['identityDocumentCode'],
                'social_reason' => $body['socialReason'],
                'commercial_reason' => $body['commercialReason'],
                'fiscal_address' => $body['fiscalAddress'],
                'email' => $body['email'],
                'telephone' => $body['telephone'],
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
            Authorization($this->connection, 'cliente', 'eliminar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $this->customerModel->DeleteById($body['customerId']);
            $res->success = true;
            $res->message = 'El registro se eliminó exitosamente';
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function validateInput($body)
    {
        $collector = new ErrorCollector();
        $collector->setSeparator('</br>');
        if (trim($body['documentNumber'] ?? '') == '') {
            $collector->addError('documentNumber', 'El número del documento es inválido');
        }
        if (trim($body['identityDocumentCode'] ?? '') == '') {
            $collector->addError('identityDocumentCode', 'No se especificó el tipo de documento de identificación');
        }
        if (trim($body['socialReason'] ?? '') == '') {
            $collector->addError('socialReason', 'No se especificó la razón social');
        }

        $identityDocValidate = ValidateIdentityDocumentNumber($body['documentNumber'], $body['identityDocumentCode']);
        if (!$identityDocValidate->success) {
            $collector->addError('documentNumber', $identityDocValidate->message);
        }

        return $collector->getResult();
    }
}
