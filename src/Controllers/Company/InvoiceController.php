<?php

require_once MODEL_PATH . '/Invoice.php';
require_once MODEL_PATH . '/Catalogue/CatCurrencyTypeCode.php';
require_once MODEL_PATH . '/Catalogue/CatDocumentTypeCode.php';
require_once MODEL_PATH . '/Catalogue/CatOperationTypeCode.php';
require_once MODEL_PATH . '/Catalogue/CatAffectationIgvTypeCode.php';
require_once MODEL_PATH . '/Catalogue/CatSystemIscTypeCode.php';
require_once MODEL_PATH . '/Catalogue/CatUnitMeasureTypeCode.php';
require_once MODEL_PATH . '/Catalogue/CatIdentityDocumentTypeCode.php';
require_once MODEL_PATH . '/Catalogue/CatCreditDebitTypeCode.php';
require_once MODEL_PATH . '/Catalogue/CatTransferReasonCode.php';
require_once MODEL_PATH . '/Catalogue/CatTransportModeCode.php';
require_once MODEL_PATH . '/Business.php';
require_once MODEL_PATH . '/BusinessSerie.php';

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
    private $catCreditDebitTypeCodeModel;
    private $catTransferReasonCodeModel;
    private $catTransportModeCodeModel;
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
        $this->catCreditDebitTypeCodeModel = new CatCreditDebitTypeCode($connection);
        $this->catTransferReasonCodeModel = new CatTransferReasonCode($connection);
        $this->catTransportModeCodeModel = new CatTransportModeCode($connection);
        $this->businessModel = new Business($connection);
    }

    public function index()
    {
        try {
            $message = '';
            $messageType = 'info';
            $error = [];

            $catDocumentTypeCode = $this->catDocumentTypeCodeModel->ByInCodes(['01','03','07','08']);
            $this->render('company/invoice.view.php', [
                'message' => $message,
                'error' => $error,
                'messageType' => $messageType,
                'catDocumentTypeCode' => $catDocumentTypeCode,
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
//            Authorization($this->connection, 'producto', 'listar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $invoice = $this->invoiceModel->paginate(
              $body['page'],
              $body['limit'],
              $_SESSION[SESS_CURRENT_LOCAL],
              $body['filter'],
            );
            $this->render('company/partials/invoiceTable.php', [
                'invoice' => $invoice,
            ]);
        } catch (Exception $e) {
            $this->render('500.php', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function searchBySerieNumber(){
      $res = new Result();
      try {
        // Authorization($this->connection, 'categoria', 'modificar');
        $postData = file_get_contents("php://input");
        $body = json_decode($postData, true);
        $body['localId'] = $_SESSION[SESS_CURRENT_LOCAL];

        $res->result = $this->invoiceModel->searchBySerieNumber($body);
        $res->success = true;
      } catch (Exception $e) {
        $res->message = $e->getMessage();
      }
      echo json_encode($res);
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
            $res->result = $businessSerieModel->getNextNumber($body);
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
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
                $invoice = $this->invoiceModel->getById($_GET['invoiceId']);
            }
            $invoiceDocumentCode = $_GET['documentCode'];

            $businessSerieModel = new BusinessSerie($this->connection);

            if(($invoiceDocumentCode === '07' || $invoiceDocumentCode === '08') && isset($invoice['document_code'])){
                $invoiceSerieNumber = $businessSerieModel->getDocumentSerieNumberStartWith([
                    'businessLocalId' => $_SESSION[SESS_CURRENT_LOCAL],
                    'documentCode' => $invoiceDocumentCode,
                    'startWith' => $invoice['document_code'] == '01' ? 'F' : 'B',
                ]);
            } else {
                $invoiceSerieNumber = $businessSerieModel->getDocumentSerieNumber([
                    'businessLocalId' => $_SESSION[SESS_CURRENT_LOCAL],
                    'documentCode' => $invoiceDocumentCode,
                ]);
            }

            $catCreditDebitTypeCode = $this->catCreditDebitTypeCodeModel->getByDocumentCode($invoiceDocumentCode);
            $catDocumentTypeCode = $this->catDocumentTypeCodeModel->ByInCodes(['01','03','07','08']);
            $catDocumentTypeCodeUpdate = $this->catDocumentTypeCodeModel->ByInCodes(['01','03']);
            $catCurrencyTypeCode = $this->catCurrencyTypeCodeModel->getAll();
            $catIdentityDocumentTypeCode = $this->catIdentityDocumentTypeCodeModel->getAll();
            $catOperationTypeCode = $this->catOperationTypeCodeModel->getAll();
            $catTransferReasonCode = $this->catTransferReasonCodeModel->getAll();
            $catTransportModeCode = $this->catTransportModeCodeModel->getAll();
            $invoiceItemTemplate = $this->invoiceItemTemplate();

            $this->render('company/newInvoice.view.php', [
                'message' => $message,
                'messageType' => $messageType,

                'catDocumentTypeCode' => $catDocumentTypeCode,
                'catDocumentTypeCodeUpdate' => $catDocumentTypeCodeUpdate,
                'catOperationTypeCode' => $catOperationTypeCode,
                'catCurrencyTypeCode' => $catCurrencyTypeCode,
                'catIdentityDocumentTypeCode' => $catIdentityDocumentTypeCode,
                'catCreditDebitTypeCode' => $catCreditDebitTypeCode,
                'catTransferReasonCode' => $catTransferReasonCode,
                'catTransportModeCode' => $catTransportModeCode,

                'invoiceItemTemplate' => $invoiceItemTemplate,
                'invoiceSerieNumber' => $invoiceSerieNumber,
                'invoiceDocumentCode' => $invoiceDocumentCode,
                'invoice' => $invoice,
            ],'layout/company.layout.php');
        } catch (Exception $e) {
            $this->render('500.php', [
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

            $invoice['totalValue'] = $invoice['totalUnaffected'] + $invoice['totalTaxed'] + $invoice['totalExonerated'];
            $invoiceId = $this->invoiceModel->insert($invoice, $_SESSION[SESS_KEY]);

            $buildInvoice = new BuildInvoice($this->connection);
            $resRunDoc = $buildInvoice->BuildDocument($invoiceId, $_SESSION[SESS_KEY], $invoice['customer']['sendEmail']);

            $res->result = [
                'invoiceId' => $invoiceId,
                'email' => $invoice['customer']['email'],
            ];
            $res->sunat = $resRunDoc;
            $res->message = 'El documento se guardado correctamente!';
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function viewInvoice(){
        echo 'viewInvoice';
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
            $invoice = $this->invoiceModel->getAllDataById((int)$invoiceId);
            if (empty($invoice)){
                throw new Exception('No se encontró ningun documento');
            }

            if (trim($invoiceCustomerEmail) == ''){
                $invoiceCustomerEmail = $invoice['customer_email'];
            }
            if($invoiceCustomerEmail == ''){
              throw new Exception('No se especificó ningún email.');
            }

            $business = $this->businessModel->getByUserId($_SESSION[SESS_KEY]);

            $buildInvoice = new BuildInvoice($this->connection);
            $responseEmail = $buildInvoice->SendEmail($invoiceCustomerEmail,$invoice,$business);
            if (!$responseEmail->success){
                throw new Exception($responseEmail->message);
            }

            $this->invoiceModel->updateInvoiceCustomerByInvoiceId($invoiceId,[
                'email_sent'=>$responseEmail,
            ]);

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
        $affectationIgvTypeCode = $this->catAffectationIgvTypeCodeModel->getAll();
        $unitMeasureTypeCode = $this->catUnitMeasureTypeCodeModel->getAll();
        $catSystemIscTypeCode = $this->catSystemIscTypeCodeModel->getAll();

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
                        <div class="SnBtn icon" id="invoiceItemQuantityRemove${uniqueId}"><i class="fas fa-minus"></i></div>
                        <input class="SnForm-control" id="invoiceItemQuantityText${uniqueId}" type="number" step="any" style="width: 80px" value="1">
                        <div class="SnBtn icon" id="invoiceItemQuantityAdd${uniqueId}"><i class="fas fa-plus"></i></div>
                    </div>
                </td>
                <td id="invoiceItemTotalValueText${uniqueId}"></td>
                <td id="invoiceItemTotalText${uniqueId}"></td>
                <td>
                    <div class="SnBtn icon" onclick="openItemModal(\'${uniqueId}\')"><i class="fas fa-edit"></i></div>
                    <div class="SnBtn icon" onclick="removeItem(\'${uniqueId}\')"><i class="far fa-trash-alt"></i></div>
                    <div>
                        <div class="SnModal-wrapper" data-modal="invoiceItemModal${uniqueId}">
                            <div class="SnModal">
                                <div class="SnModal-close" data-modalclose="invoiceItemModal${uniqueId}"
                                    onclick="closeItemModal(\'${uniqueId}\')">
                                        <i class="fas fa-times"></i>
                                </div>
                                <div class="SnModal-header"><i class="fas fa-list-ul SnMr-2"></i> Seleccionar un Producto/Servicio</div>
                                <div class="SnModal-body">
                                    <div class="SnForm-item">
                                        <label class="SnForm-label" for="invoiceItemProductSearch${uniqueId}"><i class="fas fa-cart-plus SnMr-2"></i> Aquí puedes buscar y seleccionar tu producto/servicio!</label>
                                        <div class="SnControl-wrapper">
                                            <input class="SnForm-control lg SnControl" type="text" id="invoiceItemProductSearch${uniqueId}">
                                            <i class="fas fa-search SnControl-suffix"></i>
                                        </div>
                                       <input type="hidden" id="invoiceItemProductCode${uniqueId}">
                                    </div>
                                    <div class="SnMb-5" style="border-bottom: 1px solid var(--snColorBorder);"></div>
                                    <div class="SnCollapse" data-collapse="invoiceProductData${uniqueId}">
                                        <div class="SnGrid m-grid-3">
                                            <div class="SnForm-item required">
                                                <label class="SnForm-label" for="invoiceItemAffectationCode${uniqueId}">Tipo Afectación IGV</label>
                                                <select id="invoiceItemAffectationCode${uniqueId}" class="SnForm-control jsInvoiceItemAffectationCode">
                                                    ' . $affectationIgvTemplate . '
                                                </select>
                                            </div>
                                            <div class="SnForm-item required">
                                                <label class="SnForm-label" for="invoiceItemUnitMeasure${uniqueId}">Und/Medida</label>
                                                <select id="invoiceItemUnitMeasure${uniqueId}" class="SnForm-control">
                                                    ' . $unitMeasureTemplate . '
                                                </select>
                                            </div>
                                            <div class="SnForm-item required">
                                                <label class="SnForm-label" for="invoiceItemQuantity${uniqueId}">Cantidad</label>
                                                <div class="SnControl-wrapper">
                                                    <span class="jsCurrencySymbol SnControl-prefix"></span>
                                                    <input class="SnForm-control SnControl" type="number" step="any" min="0" id="invoiceItemQuantity${uniqueId}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="SnForm-item required">
                                            <label class="SnForm-label" for="invoiceItemDescription${uniqueId}">Descripcion</label>
                                            <textarea id="invoiceItemDescription${uniqueId}" class="SnForm-control" cols="30" rows="2"></textarea>
                                        </div>
                                        <div class="SnGrid m-grid-3">
                                            <div class="SnForm-item required">
                                                <label class="SnForm-label" for="invoiceItemUnitPrice${uniqueId}">Precio/Uni(Inc.IGV)</label>
                                                <div class="SnControl-wrapper">
                                                    <span class="jsCurrencySymbol SnControl-prefix"></span>
                                                    <input class="SnForm-control SnControl" type="number" step="any" id="invoiceItemUnitPrice${uniqueId}">
                                                </div>
                                            </div>
                                            <div class="SnForm-item required">
                                                <label class="SnForm-label" for="invoiceItemUnitValue${uniqueId}">Precio/Uni(Sin.IGV)</label>
                                                <div class="SnControl-wrapper">
                                                    <i class="jsCurrencySymbol SnControl-prefix"></i>
                                                    <input class="SnForm-control SnControl" type="number" step="any" id="invoiceItemUnitValue${uniqueId}">
                                                </div>
                                            </div>
                                            <div class="SnForm-item required">
                                                <label class="SnForm-label" for="invoiceItemTotalValue${uniqueId}">Sub.Total</label>
                                                <div class="SnControl-wrapper">
                                                    <span class="jsCurrencySymbol SnControl-prefix"></span>
                                                    <input class="SnForm-control SnControl jsInvoiceItemTotalValue" type="number" step="any" id="invoiceItemTotalValue${uniqueId}" readonly>
                                                </div>
                                                <input type="hidden" id="invoiceItemTotalValueDecimal${uniqueId}">
                                            </div>
                                        </div>
                                        <div class="SnGrid m-grid-3">
                                            <div class="SnForm-item">
                                                <label class="SnForm-label" for="invoiceItemIscSystem${uniqueId}">Sistema ISC</label>
                                                <select id="invoiceItemIscSystem${uniqueId}" class="SnForm-control">
                                                    ' . $systemIscTypeTemplate . '
                                                </select>
                                            </div>
                                            <div class="SnForm-item">
                                                <label class="SnForm-label" for="invoiceItemIscTax${uniqueId}">Tasa ISC</label>
                                                <div class="SnControl-wrapper">
                                                    <span class="jsCurrencySymbol SnControl-prefix"></span>
                                                    <input class="SnForm-control SnControl" type="number" step="any" id="invoiceItemIscTax${uniqueId}">
                                                </div>
                                            </div>
                                            <div class="SnForm-item">
                                                <label class="SnForm-label" for="invoiceItemIsc${uniqueId}">ISC</label>
                                                <div class="SnControl-wrapper">
                                                    <span class="jsCurrencySymbol SnControl-prefix"></span>
                                                    <input class="SnForm-control SnControl jsInvoiceItemIsc" type="number" step="any" id="invoiceItemIsc${uniqueId}" readonly>
                                                </div>
                                                <input type="hidden" id="invoiceItemTotalBaseIsc${uniqueId}">
                                            </div>
                                        </div>
                                        <div class="SnGrid m-grid-3">
                                            <div class="SnForm-item required">
                                                <label class="SnForm-label" for="invoiceItemDiscount${uniqueId}">Descuento</label>
                                                <div class="SnControl-wrapper">
                                                    <span class="jsCurrencySymbol SnControl-prefix"></span>
                                                    <input class="SnForm-control SnControl jsInvoiceItemDiscount" type="number" step="any" min="0" id="invoiceItemDiscount${uniqueId}">
                                                </div>
                                            </div>
                                            <div class="SnForm-item required">
                                                <label class="SnForm-label" for="invoiceItemIgv${uniqueId}">IGV (18%)</label>
                                                <div class="SnControl-wrapper">
                                                    <span class="jsCurrencySymbol SnControl-prefix"></span>
                                                    <input class="SnForm-control SnControl jsInvoiceItemIgv" type="number" step="any" id="invoiceItemIgv${uniqueId}" readonly>
                                                </div>
                                                <input type="hidden" id="invoiceItemTotalBaseIgv${uniqueId}">
                                            </div>
                                            <div class="SnForm-item required">
                                                <label class="SnForm-label" for="invoiceItemTotal${uniqueId}">Total</label>
                                                <div class="SnControl-wrapper">
                                                    <span class="jsCurrencySymbol SnControl-prefix"></span>
                                                    <input type="number" step="any" class="SnForm-control SnControl jsInvoiceItemTotal" id="invoiceItemTotal${uniqueId}" readonly>
                                                </div>
                                                <input type="hidden" id="invoiceItemTotalDecimal${uniqueId}">
                                            </div>
                                        </div>
                                        <div class="SnForm-item">
                                            <div class="SnSwitch">
                                                <input class="SnSwitch-control" type="checkbox" id="invoiceItemPlasticBagTaxEnabled${uniqueId}">
                                                <label class="SnSwitch-label" for="invoiceItemPlasticBagTaxEnabled${uniqueId}"><i class="fas fa-shopping-bag SnMr-2"></i>¿Es Afecto al ICBPER?</label>
                                            </div>
                                            <input type="hidden" id="invoiceItemPlasticQuantity${uniqueId}">
                                            <input type="hidden" id="invoiceItemPlasticBagTax${uniqueId}" class="jsInvoicePlasticBagTax">
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
