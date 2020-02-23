<?php

require_once MODEL_PATH . '/Company/Invoice.php';
require_once MODEL_PATH . '/Catalogue/CatCurrencyTypeCode.php';
require_once MODEL_PATH . '/Catalogue/CatDocumentTypeCode.php';
require_once MODEL_PATH . '/Catalogue/CatOperationTypeCode.php';
require_once MODEL_PATH . '/Catalogue/CatAffectationIgvTypeCode.php';
require_once MODEL_PATH . '/Catalogue/CatSystemIscTypeCode.php';
require_once MODEL_PATH . '/Catalogue/CatUnitMeasureTypeCode.php';
require_once MODEL_PATH . '/Catalogue/CatIdentityDocumentTypeCode.php';
require_once MODEL_PATH . '/Company/Business.php';
require_once MODEL_PATH . '/Company/BusinessSerie.php';

require_once ROOT_DIR . '/src/Services/BuildInvoice.php';
require_once ROOT_DIR . '/src/Services/SendManager/EmailManager.php';

class InvoiceController extends Controller
{
    private $catCurrencyTypeCodeModel;
    private $catDocumentTypeCodeModel;
    private $catOperationTypeCodeModel;
    private $catIdentityDocumentTypeCodeModel;
    private $catAffectationIgvTypeCodeModel;
    private $catSystemIscTypeCodeModel;
    private $catUnitMeasureTypeCodeModel;
    private $invoiceModel;
    private $businessModel;
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->invoiceModel = new Invoice($connection);
        $this->catCurrencyTypeCodeModel = new CatCurrencyTypeCode($connection);
        $this->catDocumentTypeCodeModel = new CatDocumentTypeCode($connection);
        $this->catOperationTypeCodeModel = new CatOperationTypeCode($connection);
        $this->catAffectationIgvTypeCodeModel = new CatAffectationIgvTypeCode($connection);
        $this->catSystemIscTypeCodeModel = new CatSystemIscTypeCode($connection);
        $this->catUnitMeasureTypeCodeModel = new CatUnitMeasureTypeCode($connection);
        $this->catIdentityDocumentTypeCodeModel = new CatIdentityDocumentTypeCode($connection);
        $this->businessModel = new Business($connection);
    }

    public function index()
    {
        try {
            $message = '';
            $messageType = 'info';
            $error = [];

            $this->render('company/invoice.php', [
                'message' => $message,
                'error' => $error,
                'messageType' => $messageType,
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
//            Authorization($this->connection, 'producto', 'listar');
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
            $filter = $_POST['filter'] ?? [];

            $invoice = $this->invoiceModel->Paginate($page, $limit, $_SESSION[SESS_CURRENT_LOCAL], $filter);
            $this->render('company/partials/invoiceTable.php', [
                'invoice' => $invoice,
            ]);
        } catch (Exception $e) {
            $this->render('Public/500.php', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function getNextDocumentNumber()
    {
        $res = new Result();
        try {
//            Authorization($this->connection, 'categoria', 'modificar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);
            $body['businessLocalId'] = $_SESSION[SESS_CURRENT_LOCAL];

            $businessSerieModel = new BusinessSerie($this->connection);
            $res->result = $businessSerieModel->GetNextNumber($body);
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function formTemplate(){
        try {
            $businessSerieModel = new BusinessSerie($this->connection);

            $catDocumentTypeCode = $this->catDocumentTypeCodeModel->ByInCodes(['01','03','07','08']);
            $catCurrencyTypeCode = $this->catCurrencyTypeCodeModel->GetAll();
            $catIdentityDocumentTypeCode = $this->catIdentityDocumentTypeCodeModel->GetAll();
            $catOperationTypeCode = $this->catOperationTypeCodeModel->GetAll();

            $invoiceItemTemplate = $this->invoiceItemTemplate();

            $invoiceType = $businessSerieModel->GetDocumentSerieNumber([
                'localId' => $_SESSION[SESS_CURRENT_LOCAL],
                'documentCode' => '01',
            ]);

            $this->render('Company/partials/invoiceFormTemplate.php', [
                'catDocumentTypeCode' => $catDocumentTypeCode,
                'catOperationTypeCode' => $catOperationTypeCode,
                'catCurrencyTypeCode' => $catCurrencyTypeCode,
                'catIdentityDocumentTypeCode' => $catIdentityDocumentTypeCode,

                'invoiceItemTemplate' => $invoiceItemTemplate,
                'invoiceType' => $invoiceType,
            ]);
        } catch (Exception $e) {
            $this->render('Public/500.php', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function newInvoice(){
        try {
            $message = '';
            $messageType = 'info';
            $invoice = [];

            if (!isset($_GET['documentCode'])){
                throw new Exception('No se ningun documento');
            }
            if (isset($_GET['invoiceId'])){
                $invoice = $this->invoiceModel->GetById($_GET['invoiceId']);
            }
            $invoiceDocumentCode = $_GET['documentCode'];

            $businessSerieModel = new BusinessSerie($this->connection);
            $invoiceSerieNumber = $businessSerieModel->GetDocumentSerieNumber([
                'businessLocalId' => $_SESSION[SESS_CURRENT_LOCAL],
                'documentCode' => $invoiceDocumentCode,
            ]);

            $catDocumentTypeCode = $this->catDocumentTypeCodeModel->ByInCodes(['01','03','07','08']);
            $catCurrencyTypeCode = $this->catCurrencyTypeCodeModel->GetAll();
            $catIdentityDocumentTypeCode = $this->catIdentityDocumentTypeCodeModel->GetAll();
            $catOperationTypeCode = $this->catOperationTypeCodeModel->GetAll();
            $invoiceItemTemplate = $this->invoiceItemTemplate();

            $this->render('Company/newInvoice.php', [
                'message' => $message,
                'messageType' => $messageType,

                'catDocumentTypeCode' => $catDocumentTypeCode,
                'catOperationTypeCode' => $catOperationTypeCode,
                'catCurrencyTypeCode' => $catCurrencyTypeCode,
                'catIdentityDocumentTypeCode' => $catIdentityDocumentTypeCode,

                'invoiceItemTemplate' => $invoiceItemTemplate,
                'invoiceSerieNumber' => $invoiceSerieNumber,
                'invoiceDocumentCode' => $invoiceDocumentCode,
                'invoice' => $invoice,
            ]);
        } catch (Exception $e) {
            $this->render('Public/500.php', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function createInvoice(){
        $res = new Result();
        try {
            $postData = file_get_contents("php://input");
            $invoice = json_decode($postData, true);

            $invoice['localId'] = $_SESSION[SESS_CURRENT_LOCAL];
            $invoice['timeOfIssue'] = date('H:i:s');
            $invoice['percentageIgv'] = 18.00;
//            $invoice['itinerant_enable'] = ($invoice['itinerant_enable'] ?? false) == 'on' ? 1 : 0;
//            $invoice['prepayment_regulation'] = ($invoice['prepayment_regulation'] ?? false) == 'on' ? 1 : 0;
            $invoice['totalValue'] = $invoice['totalUnaffected'] + $invoice['totalTaxed'] + $invoice['totalExonerated'];
            $invoiceId = $this->invoiceModel->Insert($invoice, $_SESSION[SESS_KEY]);

            $buildInvoice = new BuildInvoice($this->connection);
            $resRunDoc = $buildInvoice->BuildDocument($invoiceId,$_SESSION[SESS_KEY]);

            $res->result = $resRunDoc;
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function viewInvoice(){
        echo 'viewInvoice';
    }

    public function viewUpdateInvoice(){
        echo 'viewUpdateInvoice';
    }

    public function updateInvoice(){
        echo 'updateInvoice';
    }

    public function search()
    {
        $res = new Result();
        try { } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function resend()
    {
        $res = new Result();
        try { 
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $buildInvoice = new BuildInvoice($this->connection);
            $res = $buildInvoice->BuildDocument($body['invoiceId'],$_SESSION[SESS_KEY]);
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function sendEmail()
    {
        $res = new Result();
        try {
//            Authorization($this->connection, 'categoria', 'modificar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $invoiceId = $body['invoiceId'];
            $invoiceCustomerEmail = $body['invoiceCustomerEmail'];
            $invoice = $this->invoiceModel->GetAllDataById((int)$invoiceId);
            if (empty($invoice)){
                throw new Exception('No se encontró ningun documento');
            }

            if (trim($invoiceCustomerEmail) == ''){
                $invoiceCustomerEmail = $invoice['customer_email'];
            }

            $responseEmail = EmailManager::Send(
                $invoiceCustomerEmail,
                "{$invoice['document_type_code_description']} {$invoice['serie']}-{$invoice['number']} | {$invoice['customer_social_reason']}",
                'paul.antezana.2@gmail.com',
                $invoice['customer_social_reason'],
                [
                    'documentDescription' => $invoice['document_type_code_description'],
                    'serie' => $invoice['serie'],
                    'number' => $invoice['number'],
                    'socialReason' => $invoice['customer_social_reason'],
                    'dateOfIssue' => $invoice['date_of_issue'],
                    'dateOfDue' => $invoice['date_of_due'],
                    'total' => "{$invoice['currency_type_code_symbol']} {$invoice['total']}",
                    'documentUrl' => HOST . URL_PATH .  "/query?ruc=sss&serie={$invoice['serie']}&number={$invoice['number']}",
                ],
                [
                    ROOT_DIR . $invoice['pdf_url'],
                    ROOT_DIR . $invoice['xml_url'],
                ]
            );

            if (!$responseEmail){
                throw new Exception('No se pudo enviar el correo');
            }
            $res->message = 'El correo se envio exitosamente al cliente';
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    private function validateInvoice()
    { }

    private function invoiceItemTemplate()
    {
        $affectationIgvTypeCode = $this->catAffectationIgvTypeCodeModel->GetAll();
        $unitMeasureTypeCode = $this->catUnitMeasureTypeCodeModel->GetAll();
        $catSystemIscTypeCode = $this->catSystemIscTypeCodeModel->GetAll();

        $affectationIgvTemplate = '';
        foreach ($affectationIgvTypeCode ?? [] as $row){
            $affectationIgvTemplate .= "<option value='{$row['code']}'>{$row['description']}</option>" . PHP_EOL;
        }

        $unitMeasureTemplate = '';
        foreach ($unitMeasureTypeCode ?? [] as $row){
            $unitMeasureTemplate .= "<option value='{$row['code']}'>{$row['description']}</option>" . PHP_EOL;
        }

        $systemIscTypeTemplate = '';
        foreach ($catSystemIscTypeCode ?? [] as $row){
            $systemIscTypeTemplate .= "<option value='{$row['code']}'>{$row['description']}</option>" . PHP_EOL;
        }

        return '
            <tr id="invoiceItem${uniqueId}" data-uniqueid="${uniqueId}">
                <td  id="invoiceItemDescriptionText${uniqueId}"></td>
                <td  id="invoiceItemUnitPriceText${uniqueId}"></td>
                <td>
                    <div class="SnControl-group">
                        <div class="SnBtn" id="invoiceItemQuantityRemove${uniqueId}"><i class="icon-minus2"></i></div>
                        <input class="SnForm-control" id="invoiceItemQuantityText${uniqueId}" type="number" step="any" style="width: 80px" value="1">
                        <div class="SnBtn" id="invoiceItemQuantityAdd${uniqueId}"><i class="icon-plus2"></i></div>
                    </div>
                </td>
                <td id="invoiceItemTotalValueText${uniqueId}"></td>
                <td id="invoiceItemTotalText${uniqueId}"></td>
                <td>
                    <div class="SnBtn icon" onclick="openItemModal(\'${uniqueId}\')"><i class="icon-pencil"></i></div>
                    <div class="SnBtn icon" onclick="removeItem(\'${uniqueId}\')"><i class="icon-trash-alt"></i></div>
                    <div>
                        <div class="SnModal-wrapper" data-modal="invoiceItemModal${uniqueId}">
                            <div class="SnModal">
                                <div class="SnModal-close" data-modalclose="invoiceItemModal${uniqueId}"
                                    onclick="closeItemModal(\'${uniqueId}\')">
                                        <i class="icon-cross"></i>
                                </div>
                                <div class="SnModal-header"><i class="icon-list2 SnMr-2"></i> Seleccionar un Producto/Servicio</div>
                                <div class="SnModal-body">
                                    <div class="SnForm-item">
                                        <label class="SnForm-label" for="invoiceItemProductSearch${uniqueId}"><i class="icon-cart-add SnMr-2"></i> Aquí puedes buscar y seleccionar tu producto/servicio!</label>
                                        <div class="SnControl-wrapper">
                                            <input class="SnForm-control lg SnControl" type="text" id="invoiceItemProductSearch${uniqueId}">
                                            <i class="icon-search4 SnControl-suffix"></i>
                                        </div>
                                       <input type="hidden" id="invoiceProductCode${uniqueId}" name="invoice[item][${uniqueId}][productCode]">
                                    </div>
                                    <div class="SnCollapse" data-collapse="invoiceProductData${uniqueId}">
                                        <div class="SnGrid m-grid-3">
                                            <div class="SnForm-item required">
                                                <label class="SnForm-label" for="invoiceItemAffectationCode${uniqueId}">Tipo Afectación IGV</label>
                                                <select name="invoice[item][${uniqueId}][affectationCode]" id="invoiceItemAffectationCode${uniqueId}" class="SnForm-control jsInvoiceItemAffectationCode">
                                                    ' . $affectationIgvTemplate . '
                                                </select>
                                            </div>
                                            <div class="SnForm-item required">
                                                <label class="SnForm-label" for="invoiceItemUnitMeasure${uniqueId}">Und/Medida</label>
                                                <select name="invoice[item][${uniqueId}][unitMeasure]" id="invoiceItemUnitMeasure${uniqueId}" class="SnForm-control">
                                                    ' . $unitMeasureTemplate . '
                                                </select>
                                            </div>
                                            <div class="SnForm-item required">
                                                <label class="SnForm-label" for="invoiceItemQuantity${uniqueId}">Cantidad</label>
                                                <div class="SnControl-wrapper">
                                                    <span class="jsCurrencySymbol SnControl-prefix"></span>
                                                    <input class="SnForm-control SnControl" type="number" step="any" min="0"
                                                        id="invoiceItemQuantity${uniqueId}" name="invoice[item][${uniqueId}][quantity]">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="SnForm-item required">
                                            <label class="SnForm-label" for="invoiceItemDescription${uniqueId}">Descripcion</label>
                                            <div class="SnControl-wrapper">
                                                <i class="icon-file-text2 SnControl-prefix"></i>
                                                <input class="SnForm-control SnControl" type="text"
                                                    id="invoiceItemDescription${uniqueId}"
                                                    name="invoice[item][${uniqueId}][description]">
                                            </div>
                                        </div>
                                        <div class="SnGrid m-grid-3">
                                            <div class="SnForm-item required">
                                                <label class="SnForm-label" for="invoiceItemUnitPrice${uniqueId}">Precio/Uni(Inc.IGV)</label>
                                                <div class="SnControl-wrapper">
                                                    <span class="jsCurrencySymbol SnControl-prefix"></span>
                                                    <input class="SnForm-control SnControl" type="number" step="any"
                                                        id="invoiceItemUnitPrice${uniqueId}"
                                                        name="invoice[item][${uniqueId}][unitPrice]">
                                                </div>
                                            </div>
                                            <div class="SnForm-item required">
                                                <label class="SnForm-label" for="invoiceItemUnitValue${uniqueId}">Precio/Uni(Sin.IGV)</label>
                                                <div class="SnControl-wrapper">
                                                    <i class="jsCurrencySymbol SnControl-prefix"></i>
                                                    <input class="SnForm-control SnControl" type="number" step="any"
                                                        id="invoiceItemUnitValue${uniqueId}"
                                                        name="invoice[item][${uniqueId}][unitValue]">
                                                </div>
                                            </div>
                                            <div class="SnForm-item required">
                                                <label class="SnForm-label" for="invoiceItemTotalValue${uniqueId}">Sub.Total</label>
                                                <div class="SnControl-wrapper">
                                                    <span class="jsCurrencySymbol SnControl-prefix"></span>
                                                    <input class="SnForm-control SnControl jsInvoiceItemTotalValue" type="number" step="any"
                                                        id="invoiceItemTotalValue${uniqueId}"
                                                        name="invoice[item][${uniqueId}][totalValue]" readonly>
                                                </div>
                                                <input type="hidden" id="invoiceItemTotalValueDecimal${uniqueId}">
                                            </div>
                                        </div>
                                        <div class="SnGrid m-grid-3">
                                            <div class="SnForm-item">
                                                <label class="SnForm-label" for="invoiceItemIscSystem${uniqueId}">Sistema ISC</label>
                                                <select name="invoice[item][${uniqueId}][iscSystem]" id="invoiceItemIscSystem${uniqueId}" class="SnForm-control">
                                                    ' . $systemIscTypeTemplate . '
                                                </select>
                                            </div>
                                            <div class="SnForm-item">
                                                <label class="SnForm-label" for="invoiceItemIscTax${uniqueId}">Tasa ISC</label>
                                                <div class="SnControl-wrapper">
                                                    <span class="jsCurrencySymbol SnControl-prefix"></span>
                                                    <input class="SnForm-control SnControl" type="number" step="any"
                                                        id="invoiceItemIscTax${uniqueId}" name="invoice[item][${uniqueId}][iscTax]">
                                                </div>
                                            </div>
                                            <div class="SnForm-item">
                                                <label class="SnForm-label" for="invoiceItemIsc${uniqueId}">ISC</label>
                                                <div class="SnControl-wrapper">
                                                    <span class="jsCurrencySymbol SnControl-prefix"></span>
                                                    <input class="SnForm-control SnControl jsInvoiceItemIsc" type="number" step="any"
                                                        id="invoiceItemIsc${uniqueId}" name="invoice[item][${uniqueId}][isc]" readonly>
                                                </div>
                                                <input type="hidden" id="invoiceItemTotalBaseIsc${uniqueId}" name="invoice[item][${uniqueId}][totalBaseIsc]">
                                            </div>
                                        </div>
                                        <div class="SnGrid m-grid-3">
                                            <div class="SnForm-item required">
                                                <label class="SnForm-label" for="invoiceItemDiscount${uniqueId}">Descuento</label>
                                                <div class="SnControl-wrapper">
                                                    <span class="jsCurrencySymbol SnControl-prefix"></span>
                                                    <input class="SnForm-control SnControl jsInvoiceItemDiscount" type="number" step="any" min="0"
                                                        id="invoiceItemDiscount${uniqueId}" name="invoice[item][${uniqueId}][discount]">
                                                </div>
                                            </div>
                                            <div class="SnForm-item required">
                                                <label class="SnForm-label" for="invoiceItemIgv${uniqueId}">IGV (18%)</label>
                                                <div class="SnControl-wrapper">
                                                    <span class="jsCurrencySymbol SnControl-prefix"></span>
                                                    <input class="SnForm-control SnControl jsInvoiceItemIgv" type="number" step="any"
                                                        id="invoiceItemIgv${uniqueId}" name="invoice[item][${uniqueId}][igv]" readonly>
                                                </div>
                                                <input type="hidden" id="invoiceItemTotalBaseIgv${uniqueId}" name="invoice[item][${uniqueId}][totalBaseIgv]">
                                            </div>
                                            <div class="SnForm-item required">
                                                <label class="SnForm-label" for="invoiceItemTotal${uniqueId}">Total</label>
                                                <div class="SnControl-wrapper">
                                                    <span class="jsCurrencySymbol SnControl-prefix"></span>
                                                    <input type="number" step="any" class="SnForm-control SnControl jsInvoiceItemTotal"
                                                        id="invoiceItemTotal${uniqueId}" name="invoice[item][${uniqueId}][total]" readonly>
                                                </div>
                                                <input type="hidden" id="invoiceItemTotalDecimal${uniqueId}">
                                            </div>
                                        </div>
                                        <div class="SnBtn primary block" onclick="closeItemModal(\'${uniqueId}\')">Aceptar</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>';
    }
}
