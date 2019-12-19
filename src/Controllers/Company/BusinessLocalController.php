<?php

require_once MODEL_PATH . '/Company/Business.php';
require_once MODEL_PATH . '/Company/BusinessLocal.php';
require_once MODEL_PATH . '/Company/BusinessSerie.php';
require_once MODEL_PATH . '/Catalogue/CatDocumentTypeCode.php';

class BusinessLocalController extends Controller
{
    private $connection;
    private $businessModel;
    private $businessLocalModel;
    private $catDocumentTypeCodeModel;
    private $businessSerieModel;

    public function __construct($connection)
    {
        $this->connection = $connection;
        $this->businessModel = new Business($this->connection);
        $this->businessLocalModel = new BusinessLocal($this->connection);
        $this->catDocumentTypeCodeModel = new CatDocumentTypeCode($this->connection);
        $this->businessSerieModel = new BusinessSerie($this->connection);
    }

    public function index()
    {
        try {
            //            Authorization($this->connection, 'usuario', 'modificar');

            $business = $this->businessModel->GetByUserId($_SESSION[SESS_KEY]);
            $itemTemplate = $this->GetItemTemplate();

            $this->render('company/businessLocal.php', [
                'business' => $business,
                'itemTemplate' => $itemTemplate,
            ]);
        } catch (Exception $e) {
            $this->render('Public/500.php', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function table()
    {
        try {
            //            Authorization($this->connection, 'usuario', 'modificar');
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
            $search = isset($_GET['search']) ? $_GET['search'] : '';

            $businessLocal = $this->businessLocalModel->Paginate($page, $limit, $search);

            $this->render('company/partials/businessLocalTable.php', [
                'businessLocal' => $businessLocal,
            ]);
        } catch (Exception $e) {
            $this->render('Public/500.php', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function id()
    {
        $res = new Result();
        try {
            //            Authorization($this->connection, 'usuario', 'modificar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $res->result = $this->businessLocalModel->GetByIdDetail($body['businessLocalId']);
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
            $body = $_POST['businessLocal'];

//            $validate = $this->validateInput($body);
//            if (!$validate->success) {
//                throw new Exception($validate->message);
//            }

            $businessModel = new Business($this->connection);
            $body['businessId'] = $businessModel->GetByUserId($_SESSION[SESS_KEY])['business_id'];

            $res->result = $this->businessLocalModel->Insert($body, $_SESSION[SESS_KEY]);
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
            $body = $_POST['businessLocal'];

//            $validate = $this->validateInput($body);
//            if (!$validate->success) {
//                throw new Exception($validate->message);
//            }

            $this->businessLocalModel->Update($body, $_SESSION[SESS_KEY]);

            $res->success = true;
            $res->message = 'El registro se actualizo exitosamente';
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

    private function GetItemTemplate(){
        $catDocumentTypeCode = $this->catDocumentTypeCodeModel->GetAll();

        $documentTypeCodeTemplate = '';
        foreach ($catDocumentTypeCode ?? [] as $row){
            $documentTypeCodeTemplate .= "<option value='{$row['code']}'>{$row['description']}</option>" . PHP_EOL;
        }

        return '<tr id="businessLocalItem${uniqueId}" data-uniqueId="${uniqueId}">
            <td>
                <select class="SnForm-control" name="businessLocal[item][${uniqueId}][documentCode]" id="documentCode${uniqueId}" required>
                    ' . $documentTypeCodeTemplate . '
                </select>
                <input type="hidden" name="businessLocal[item][${uniqueId}][businessSerieId]" id="businessSerieId${uniqueId}">
            </td>
            <td>
                <input type="text" class="SnForm-control" name="businessLocal[item][${uniqueId}][serie]" id="serie${uniqueId}" required>
            </td>
            <td>
                <div class="SnBtn" title="Quitar item" onclick="BusinessLocalSerieRemoveItem(${uniqueId})">
                    <i class="icon-trash-alt"></i>
                </div>
            </td>
        </tr>';
    }
}
