<?php

require_once MODEL_PATH . '/Business.php';
require_once MODEL_PATH . '/BusinessLocal.php';
require_once MODEL_PATH . '/BusinessSerie.php';
require_once MODEL_PATH . '/Catalogue/CatDocumentTypeCode.php';

class BusinessLocalController extends Controller
{
    private $connection;
    private $businessModel;
    private $businessLocalModel;
    private $catDocumentTypeCodeModel;

    public function __construct($connection)
    {
        $this->connection = $connection;
        $this->businessModel = new Business($this->connection);
        $this->businessLocalModel = new BusinessLocal($this->connection);
        $this->catDocumentTypeCodeModel = new CatDocumentTypeCode($this->connection);
    }

    public function index()
    {
        try {
            Authorization($this->connection, 'local', 'listar');
            $business = $this->businessModel->GetByUserId($_SESSION[SESS_KEY]);
            $itemTemplate = $this->GetItemTemplate();

            $this->render('company/businessLocal.php', [
                'business' => $business,
                'itemTemplate' => $itemTemplate,
            ]);
        } catch (Exception $e) {
            $this->render('500.php', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function table()
    {
        try {
            Authorization($this->connection, 'local', 'listar');
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
            $search = isset($_GET['search']) ? $_GET['search'] : '';

            $business = $this->businessModel->GetByUserId($_SESSION[SESS_KEY]);
            $businessLocal = $this->businessLocalModel->PaginateByBusinessId($page, $limit, $search, $business['business_id']);

            $this->render('company/partials/businessLocalTable.php', [
                'businessLocal' => $businessLocal,
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
            Authorization($this->connection, 'local', 'modificar');
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
            Authorization($this->connection, 'local', 'crear');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $validate = $this->validateInput($body);
            if (!$validate->success) {
                $res->error = $validate->error;
                throw new Exception($validate->message);
            }

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
            Authorization($this->connection, 'local', 'modificar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $validate = $this->validateInput($body);
            if (!$validate->success) {
                $res->error = $validate->error;
                throw new Exception($validate->message);
            }
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
        $res = new ErrorCollector();

        if (($body['sunatCode']) == '') {
            $res->addError('sunatCode','Falta ingresar el codigo');
        }
        if (($body['shortName']) == '') {
            $res->addError('shortName','Falta ingresar el nombre de sucursal');
        }
        if (($body['address']) == '') {
            $res->addError('shortName','Falta ingresar el dirección');
        }
        if (empty($body['item'])) {
            $res->addError('item','Falta ingresar el item');
            return $res->getResult();
        }

        foreach ($body['item'] as $key => $row) {
            if (strlen($row['serie']) != 4) {
                $res->addErrorRowChildren('item', $key, 'serie', 'La serie debe contener 4 digitos');
            }
            if ($row['documentCode'] == '01' && $row['contingency'] == false) {
                if (!(substr($row['serie'], 0, 1) == 'F')) {
                    $res->addErrorRowChildren('item', $key, 'serie', 'Numero de serie incorecto');
                }
            }
            if ($row['documentCode'] == '03' && $row['contingency'] == false) {
                if (!(substr($row['serie'], 0, 1) == 'B')) {
                    $res->addErrorRowChildren('item', $key, 'serie', 'Numero de serie incorecto');
                }
            }
            if ($row['documentCode'] == '07' && $row['contingency'] == false) {
                if (!(substr($row['serie'], 0, 1) == 'F' || substr($row['serie'], 0, 1) == 'B')) {
                    $res->addErrorRowChildren('item', $key, 'serie', 'Numero de serie incorecto');
                }
            }
            if ($row['documentCode'] == '08' && $row['contingency'] == false) {
                if (!(substr($row['serie'], 0, 1) == 'F' || substr($row['serie'], 0, 1) == 'B')) {
                    $res->addErrorRowChildren('item', $key, 'serie', 'Numero de serie incorecto');
                }
            }
            if($row['contingency']){
                $match = preg_match('/[0-9]{4}/',$row['serie']);
                if(!$match){
                    $res->addErrorRowChildren('item', $key, 'serie', 'Numero de serie incorecto');
                }
            }
            if ($row['documentCode'] == '09') {
                if (!(substr($row['serie'], 0, 1) == 'T')) {
                    $res->addErrorRowChildren('item', $key, 'serie', 'Numero de serie incorecto');
                }
            }
        }

        return $res->getResult();
    }

    private function GetItemTemplate()
    {
        $catDocumentTypeCode = $this->catDocumentTypeCodeModel->GetAll();

        $documentTypeCodeTemplate = '';
        foreach ($catDocumentTypeCode ?? [] as $row) {
            $documentTypeCodeTemplate .= "<option value='{$row['code']}'>{$row['description']}</option>" . PHP_EOL;
        }

        return '<tr id="businessLocalItem${uniqueId}" data-uniqueId="${uniqueId}">
            <td>
                <div class="SnForm-item" style="margin: 0">
                    <select class="SnForm-control" id="documentCode${uniqueId}" required>
                        ' . $documentTypeCodeTemplate . '
                    </select>
                </div>
                <input type="hidden" id="businessSerieId${uniqueId}">
            </td>
            <td>
                <div class="SnForm-item" style="margin: 0">
                    <div class="SnControl-wrapper">
                        <i class="icon-barcode2 SnControl-prefix"></i>   
                        <input type="text" pattern="/([0-9A-Z]){4}$/" data-pristine-pattern-message="La serie debe contener 4 digitos" class="SnForm-control SnControl" id="serie${uniqueId}" required>
                    </div>
                </div>
            </td>
            <td>
                <div class="SnSwitch" style="height: 18px">
                    <input class="SnSwitch-control" type="checkbox" id="contingency${uniqueId}">
                    <label class="SnSwitch-label" for="contingency${uniqueId}"></label>
                </div>
            </td>
            <td>
                <div class="SnBtn icon" title="Quitar item" onclick="BusinessLocalSerieRemoveItem(\'${uniqueId}\')">
                    <i class="icon-trash-alt"></i>
                </div>
            </td>
        </tr>';
    }
}
