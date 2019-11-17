<?php

require_once MODEL_PATH . '/Category.php';
require_once MODEL_PATH.'/Business.php';

class CategoryController extends Controller
{
    protected $connection;
    protected $categoryModel;
    protected $catIdentityDocumentTypeCodeModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->categoryModel = new Category($connection);
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

            $category = $this->categoryModel->Paginate($page, $limit, $search);

            $this->render('admin/partials/categoryTable.php', [
                'category' => $category,
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

            $res->result = $this->categoryModel->GetById($body['categoryId']);
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

            $businessModel = new Business($this->connection);
            $body['businessId'] = $businessModel->GetByUserId($_SESSION[SESS_KEY])['business_id'];

            $res->result = $this->categoryModel->Insert($body, $_SESSION[SESS_KEY]);
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
            $this->categoryModel->UpdateById($body['categoryId'], [
                'created_at' => $currentDate,
                'updated_user_id' => $_SESSION[SESS_KEY],

//                'parent_id' => $body['parentId'],
                'name' => $body['name'],
                'description' => $body['description'],
                'state' => $body['state'],
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

            $this->categoryModel->DeleteById($body['categoryId']);
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
            $res->message .= 'Falta ingresar el nombre | ';
            $res->success = false;
        }

        return $res;
    }
}
