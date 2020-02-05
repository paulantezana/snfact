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
            $this->render('Public/500.php', [
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
            $this->render('Public/500.php', [
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
        $res = new Result();
        $res->success = true;

        if (($body['sunatCode']) == '') {
            $res->message .= 'Falta ingresar el codigo | ';
            $res->success = false;
        }
        if (($body['shortName']) == '') {
            $res->message .= 'Falta ingresar el nombre de sucursal | ';
            $res->success = false;
        }
        if (($body['address']) == '') {
            $res->message .= 'Falta ingresar el dirección | ';
            $res->success = false;
        }
        if (empty($body['item'])) {
            $res->message .= 'Falta ingresar el item | ';
            $res->success = false;
            return $res;
        }

        foreach ($body['item'] as $row) {
            $mathcCount = 0;
            foreach ($body['item'] as $item) {
                if ($row['serie'] == $item['serie'] && $row['contingency'] == $item['contingency'] && $row['documentCode'] == $item['documentCode']) {
                    $mathcCount++;
                }
            }
            if ($mathcCount > 1) {
                $res->message .= 'Numero de serie duplicado | ';
                $res->success = false;
                return $res;
            }
            if (strlen($row['serie']) != 4) {
                $res->message .= 'Numero de serie incorecto | ';
                $res->success = false;
                return $res;
            }

            if ($row['documentCode'] == '01') {
                if (!(substr($row['serie'], 0, 1) == 'F')) {
                    $res->message .= 'Numero de serie incorecto | ';
                    $res->success = false;
                    return $res;
                }
            }
            if ($row['documentCode'] == '03') {
                if (!(substr($row['serie'], 0, 1) == 'B')) {
                    $res->message .= 'Numero de serie incorecto | ';
                    $res->success = false;
                    return $res;
                }
            }
            if ($row['documentCode'] == '07') {
                if (!(substr($row['serie'], 0, 1) == 'F' || substr($row['serie'], 0, 1) == 'B')) {
                    $res->message .= 'Numero de serie incorecto | ';
                    $res->success = false;
                    return $res;
                }
            }
            if ($row['documentCode'] == '08') {
                if (!(substr($row['serie'], 0, 1) == 'F' || substr($row['serie'], 0, 1) == 'B')) {
                    $res->message .= 'Numero de serie incorecto | ';
                    $res->success = false;
                    return $res;
                }
            }
            if ($row['documentCode'] == '09') {
                if (!(substr($row['serie'], 0, 1) == 'T')) {
                    $res->message .= 'Numero de serie incorecto | ';
                    $res->success = false;
                    return $res;
                }
            }
        }

        return $res;
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
                    <input class="SnSwitch-input" type="checkbox" id="contingency${uniqueId}">
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
