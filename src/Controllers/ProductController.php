<?php

require_once MODEL_PATH . '/Category.php';

class CategoryController extends Controller
{
    protected $connection;
    protected $customerModel;
    protected $catIdentityDocumentTypeCodeModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->customerModel = new Customer($connection);
    }

    public function index()
    {
        try {
            //            Authorization($this->connection, 'usuario', 'modificar');
            $this->render('admin/category.php');
        } catch (Exception $e) {
            echo $e->getMessage() . "\n\n" . $e->getTraceAsString();
        }
    }

    public function table()
    {
        try {
            //            Authorization($this->connection, 'usuario', 'modificar');
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
            $search = isset($_GET['search']) ? $_GET['search'] : '';

            $customer = $this->customerModel->Paginate($page, $limit, $search);

            $this->render('admin/partials/customerTable.php', [
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
            //            Authorization($this->connection, 'usuario', 'modificar');
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
            //            Authorization($this->connection, 'usuario', 'modificar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $validate = $this->validateInput($body);
            if (!$validate->success) {
                throw new Exception($validate->message);
            }

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
            //            Authorization($this->connection, 'usuario', 'modificar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $validate = $this->validateInput($body);
            if (!$validate->success) {
                throw new Exception($validate->message);
            }

            $currentDate = date('Y-m-d H:i:s');
            $this->customerModel->UpdateById($body['customerId'], [
                ':updated_at' => $currentDate,
                ':updated_user_id' => $_SESSION[SESS_KEY],
                ':business_id' => $body['businessId'],
                ':document_number' => $body['documentNumber'],
                ':identity_document_code' => $body['identityDocumentCode'],
                ':social_reason' => $body['socialReason'],
                ':commercial_reason' => $body['commercialReason'],
                ':fiscal_address' => $body['fiscalAddress'],
                ':main_email' => $body['mainEmail'],
                ':telephone' => $body['telephone'],
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
            //            Authorization($this->connection, 'usuario', 'modificar');
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
        $res = new Result();
        $res->success = true;

        if (($body['documentNumber']) == '') {
            $res->message .= 'Falta ingresar el nombre | ';
            $res->success = false;
        }

        return $res;
    }
}
