<?php

require_once MODEL_PATH . '/Product.php';
require_once MODEL_PATH . '/Business.php';
require_once MODEL_PATH . '/Catalogue/CatAffectationIgvTypeCode.php';
require_once MODEL_PATH . '/Catalogue/CatUnitMeasureTypeCode.php';
require_once MODEL_PATH . '/Category.php';
require_once MODEL_PATH . '/Catalogue/CatSystemIscTypeCode.php';
require_once MODEL_PATH . '/Catalogue/CatProductCode.php';

class ProductController extends Controller
{
    protected $connection;
    protected $productModel;
    protected $businessModel;
    protected $catIdentityDocumentTypeCodeModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->productModel = new Product($connection);
        $this->businessModel = new Business($connection);
    }

    public function index()
    {
        try {
            Authorization($this->connection, 'producto', 'listar');
            $catAffectationIgvTypeCodeModel = new CatAffectationIgvTypeCode($this->connection);
            $catUnitMeasureTypeCodeModel = new CatUnitMeasureTypeCode($this->connection);
            $catSystemIscTypeCodeModel = new CatSystemIscTypeCode($this->connection);
            $categoryModel = new Category($this->connection);
            $business = $this->businessModel->getByUserId($_SESSION[SESS_KEY]);

            $catAffectationIgvTypeCodes = $catAffectationIgvTypeCodeModel->getAll();
            $catUnitMeasureTypeCodes = $catUnitMeasureTypeCodeModel->getAll();
            $catSystemIscTypeCodes = $catSystemIscTypeCodeModel->getAll();
            $categories = $categoryModel->getAllByBusinessId($business['business_id']);

            $this->render('company/product.view.php', [
                'catAffectationIgvTypeCodes' => $catAffectationIgvTypeCodes,
                'catUnitMeasureTypeCodes' => $catUnitMeasureTypeCodes,
                'catSystemIscTypeCodes' => $catSystemIscTypeCodes,
                'categories' => $categories,
            ],'layout/company.layout.php');
        } catch (Exception $e) {
            $this->render('500.php', [
                'message' => $e->getMessage(),
            ],'layout/company.layout.php');
        }
    }

    public function table()
    {
        try {
            Authorization($this->connection, 'producto', 'listar');
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
            $search = isset($_GET['search']) ? $_GET['search'] : '';

            $business = $this->businessModel->getByUserId($_SESSION[SESS_KEY]);
            $product = $this->productModel->paginate($page, $limit, $search, $business['business_id']);

            $this->render('company/partials/productTable.php', [
                'product' => $product,
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
            Authorization($this->connection, 'producto', 'modificar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $res->result = $this->productModel->getById($body['productId']);
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
            Authorization($this->connection, 'producto', 'crear');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $validate = $this->validateInput($body);
            if (!$validate->success) {
                $res->error = $validate->error;
                throw new Exception($validate->message);
            }

            $body['businessId'] = $this->businessModel->getByUserId($_SESSION[SESS_KEY])['business_id'];

            $res->result = $this->productModel->Insert($body, $_SESSION[SESS_KEY]);
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
            Authorization($this->connection, 'producto', 'modificar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $validate = $this->validateInput($body);
            if (!$validate->success) {
                $res->error = $validate->error;
                throw new Exception($validate->message);
            }

            $currentDate = date('Y-m-d H:i:s');
            $this->productModel->updateById($body['productId'], [
                'updated_at' => $currentDate,
                'updated_user_id' => $_SESSION[SESS_KEY],

                'description' => $body['description'],
                'unit_price' => $body['unitPrice'],
                'unit_value' => $body['unitValue'],
                'bag_tax' => $body['bagTax'],
                'product_key' => $body['productKey'],
                'product_code' => $body['productCode'],
                'unit_measure_code' => $body['unitMeasureCode'],
                'affectation_code' => $body['affectationCode'],
                'system_isc_code' => $body['systemIscCode'],
                'isc' => $body['isc'],
                'category_id' => $body['categoryId'],
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
            Authorization($this->connection, 'producto', 'eliminar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $this->productModel->deleteById($body['productId']);
            $res->success = true;
            $res->message = 'El registro se eliminó exitosamente';
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function search(){
        $res = new Result();
        try{
            Authorization($this->connection, 'producto', 'modificar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $search['search'] = $body['search'];
            $search['businessId'] = $this->businessModel->getByUserId($_SESSION[SESS_KEY])['business_id'];
            $response = $this->productModel->search($search);

            $res->result = $response;
            $res->success = true;
        } catch (Exception $e){
            $res->errorMessage = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function searchProductCode(){
        $res = new Result();
        try{
//            Authorization($this->connection, 'producto', 'modificar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $catProductCodeModel = new CatProductCode($this->connection);
            $response = $catProductCodeModel->Search($body['search']);

            $res->result = $response;
            $res->success = true;
        } catch (Exception $e){
            $res->errorMessage = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function validateInput($body)
    {
        $collector = new ErrorCollector();
        if (trim($body['unitMeasureCode'] ?? '') == "") {
            $collector->addError('unitMeasureCode', 'No se especificó el código de unidad de medida SUNAT');
        }
        if (trim($body['description'] ?? '') == "") {
            $collector->addError('description', 'El campo descripción es obligatorio');
        }
        if (trim($body['productCode'] ?? '') == "") {
            $collector->addError('productCode', 'El campo codigo producto es obligatorio');
        }
        if (trim($body['categoryId'] ?? '') == "") {
            $collector->addError('productCode', 'El campo categoria es obligatorio');
        }
        if (trim($body['affectationCode'] ?? '') == "") {
            $collector->addError('affectationCode', 'No se especifico el tipo de afectación del producto');
        }
        return $collector->getResult();
    }
}
