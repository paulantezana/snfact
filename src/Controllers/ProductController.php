<?php

require_once MODEL_PATH . '/Product.php';
require_once MODEL_PATH. '/Business.php';
require_once MODEL_PATH. '/CatAffectationIgvTypeCode.php';
require_once MODEL_PATH. '/CatUnitMeasureTypeCode.php';
require_once MODEL_PATH. '/Category.php';
require_once MODEL_PATH. '/CatSystemIscTypeCode.php';
require_once MODEL_PATH. '/CatProductCode.php';

class ProductController extends Controller
{
    protected $connection;
    protected $productModel;
    protected $catIdentityDocumentTypeCodeModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->productModel = new Product($connection);
    }

    public function index()
    {
        try {
            //            Authorization($this->connection, 'usuario', 'modificar');
            $catAffectationIgvTypeCodeModel = new CatAffectationIgvTypeCode($this->connection);
            $catUnitMeasureTypeCodeModel = new CatUnitMeasureTypeCode($this->connection);
            $catSystemIscTypeCodeModel = new CatSystemIscTypeCode($this->connection);
            $catProductCodeModel = new CatProductCode($this->connection);
            $categoryModel = new Category($this->connection);

            $catAffectationIgvTypeCodes = $catAffectationIgvTypeCodeModel->GetAll();
            $catUnitMeasureTypeCodes = $catUnitMeasureTypeCodeModel->GetAll();
            $catSystemIscTypeCodes = $catSystemIscTypeCodeModel->GetAll();
            $catProductCodes = $catProductCodeModel->GetAll();
            $categories = $categoryModel->GetAll();

            $this->render('admin/product.php', [
                'catAffectationIgvTypeCodes' => $catAffectationIgvTypeCodes,
                'catUnitMeasureTypeCodes' => $catUnitMeasureTypeCodes,
                'catSystemIscTypeCodes' => $catSystemIscTypeCodes,
                'categories' => $categories,
                'catProductCodes' => $catProductCodes,
            ]);
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

            $product = $this->productModel->Paginate($page, $limit, $search);

            $this->render('admin/partials/productTable.php', [
                'product' => $product,
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

            $res->result = $this->productModel->GetById($body['productId']);
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
                $res->error = $validate->error;
                throw new Exception($validate->message);
            }

            $businessModel = new Business($this->connection);
            $body['businessId'] = $businessModel->GetByUserId($_SESSION[SESS_KEY])['business_id'];

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
            //            Authorization($this->connection, 'usuario', 'modificar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $validate = $this->validateInput($body);
            if (!$validate->success) {
                $res->error = $validate->error;
                throw new Exception($validate->message);
            }

            $currentDate = date('Y-m-d H:i:s');
            $this->productModel->UpdateById($body['productId'], [
                'updated_at' => $currentDate,
                'updated_user_id' => $_SESSION[SESS_KEY],

                'description' => $body['description'],
                'unit_price' => $body['unitPrice'],
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
            //            Authorization($this->connection, 'usuario', 'modificar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $this->productModel->DeleteById($body['productId']);
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
        if (trim($body['unitMeasureCode'] ?? '') == ""){
            $collector->addError('unitMeasureCode','No se especificó el código de unidad de medida SUNAT');
        }
        if (trim($body['description'] ?? '') == ""){
            $collector->addError('description','El campo descripción es obligatorio');
        }
        if (trim($body['productCode'] ?? '') == ""){
            $collector->addError('productCode','El campo codigo producto es obligatorio');
        }
        if (trim($body['categoryId'] ?? '') == ""){
            $collector->addError('productCode','El campo categoria es obligatorio');
        }
        if (trim($body['affectationCode'] ?? '') == ""){
            $collector->addError('affectationCode','No se especifico el tipo de afectación del producto');
        }
        return $collector->getResult();
    }
}