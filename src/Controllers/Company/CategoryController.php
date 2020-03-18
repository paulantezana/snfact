<?php

require_once MODEL_PATH . '/Category.php';
require_once MODEL_PATH . '/Business.php';

class CategoryController extends Controller
{
    protected $connection;
    protected $categoryModel;
    protected $businessModel;
    protected $catIdentityDocumentTypeCodeModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->categoryModel = new Category($connection);
        $this->businessModel = new Business($connection);
    }

    public function index()
    {
        try {
            Authorization($this->connection, 'categoria', 'listar');
            $this->render('company/category.view.php',[],'layout/company.layout.php');
        } catch (Exception $e) {
            $this->render('500.php', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function table()
    {
        try {
            Authorization($this->connection, 'categoria', 'listar');
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
            $search = isset($_GET['search']) ? $_GET['search'] : '';

            $business = $this->businessModel->getByUserId($_SESSION[SESS_KEY]);
            $category = $this->categoryModel->paginate($page, $limit, $search, $business['business_id']);

            $this->render('company/partials/categoryTable.php', [
                'category' => $category,
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
            Authorization($this->connection, 'categoria', 'modificar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $res->result = $this->categoryModel->getById($body['categoryId']);
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
            Authorization($this->connection, 'categoria', 'crear');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $validate = $this->validateInput($body);
            if (!$validate->success) {
                throw new Exception($validate->message);
            }

            $body['businessId'] = $this->businessModel->getByUserId($_SESSION[SESS_KEY])['business_id'];

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
            Authorization($this->connection, 'categoria', 'modificar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $validate = $this->validateInput($body);
            if (!$validate->success) {
                throw new Exception($validate->message);
            }

            $currentDate = date('Y-m-d H:i:s');
            $this->categoryModel->updateById($body['categoryId'], [
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
            Authorization($this->connection, 'categoria', 'eliminar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $this->categoryModel->deleteById($body['categoryId']);
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
            $res->message .= 'Falta ingresar el nombre de la categoria';
            $res->success = false;
        }

        return $res;
    }
}
