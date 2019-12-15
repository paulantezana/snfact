<?php

require_once MODEL_PATH . '/Company/Invoice.php';
require_once MODEL_PATH . '/Catalogue/CatCurrencyTypeCode.php';
require_once MODEL_PATH . '/Catalogue/CatDocumentTypeCode.php';
require_once MODEL_PATH . '/Catalogue/CatAffectationIgvTypeCode.php';
require_once MODEL_PATH . '/Catalogue/CatSystemIscTypeCode.php';
require_once MODEL_PATH . '/Catalogue/CatUnitMeasureTypeCode.php';
require_once MODEL_PATH . '/Catalogue/CatIdentityDocumentTypeCode.php';
require_once MODEL_PATH . '/Company/Business.php';

class InvoiceController extends Controller
{
    private $catCurrencyTypeCodeModel;
    private $catDocumentTypeCodeModel;
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
            $this->render('pages/500.php', [
                'message' => $e->getMessage() . "</br>" . $e->getTraceAsString(),
            ]);
        }
    }

    public function table()
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
            $this->render('pages/500.php', [
                'message' => $e->getMessage() . "</br>" . $e->getTraceAsString(),
            ]);
        }
    }

    public function newFormF()
    {
        try {
            $message = '';
            $messageType = 'info';
            $error = [];

            $catDocumentTypeCode = $this->catDocumentTypeCodeModel->GetAll();
            $catCurrencyTypeCode = $this->catCurrencyTypeCodeModel->GetAll();
            $catIdentityDocumentTypeCode = $this->catIdentityDocumentTypeCodeModel->GetAll();

            $invoiceItemTemplate = $this->invoiceItemTemplate();

            $this->render('company/newFormF.php', [
                'message' => $message,
                'error' => $error,
                'messageType' => $messageType,
                'catDocumentTypeCode' => $catDocumentTypeCode,
                'catCurrencyTypeCode' => $catCurrencyTypeCode,
                'catIdentityDocumentTypeCode' => $catIdentityDocumentTypeCode,

                'invoiceItemTemplate' => $invoiceItemTemplate,
            ]);
        } catch (Exception $e) {
            $this->render('pages/500.php', [
                'message' => $e->getMessage() . "</br>" . $e->getTraceAsString(),
            ]);
        }
    }

    public function newFormB()
    {
        try {
            $message = '';
            $messageType = 'info';
            $error = [];

            $this->render('company/newFormB.php', [
                'message' => $message,
                'error' => $error,
                'messageType' => $messageType,
            ]);
        } catch (Exception $e) {
            $this->render('pages/500.php', [
                'message' => $e->getMessage() . "</br>" . $e->getTraceAsString(),
            ]);
        }
    }

    public function newFormCreditNote()
    {
        try {
            $message = '';
            $messageType = 'info';
            $error = [];

            $this->render('company/newFormCreditNote.php', [
                'message' => $message,
                'error' => $error,
                'messageType' => $messageType,
            ]);
        } catch (Exception $e) {
            $this->render('pages/500.php', [
                'message' => $e->getMessage() . "</br>" . $e->getTraceAsString(),
            ]);
        }
    }

    public function newFormDebitNote()
    {
        try {
            $message = '';
            $messageType = 'info';
            $error = [];

            $this->render('company/newFormCreditNote.php', [
                'message' => $message,
                'error' => $error,
                'messageType' => $messageType,
            ]);
        } catch (Exception $e) {
            $this->render('pages/500.php', [
                'message' => $e->getMessage() . "</br>" . $e->getTraceAsString(),
            ]);
        }
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
        try { } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function sendEmail()
    {
        $res = new Result();
        try { } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function createF()
    {
        $res = new Result();
        try {
            $res->result = $_POST;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function createB()
    {
        $res = new Result();
        try { } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function createCreditNote()
    {
        $res = new Result();
        try { } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function createDebitNote()
    {
        $res = new Result();
        try { } catch (Exception $e) {
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
                        <div class="SnBtn"><i class="icon-plus"></i></div>
                        <input class="SnForm-control" type="number" step="any" style="width: 80px">
                        <div class="SnBtn"><i class="icon-plus"></i></div>
                    </div>
                </td>
                <td id="invoiceItemTotalValueText${uniqueId}"></td>
                <td id="invoiceItemTotalText${uniqueId}"></td>
                <td>
                    <div class="SnBtn" onclick="Invoice.openItemModal(\'${uniqueId}\')"><i class="icon-pencil"></i></div>
                    <div class="SnBtn error" onclick="Invoice.removeItem(\'${uniqueId}\')"><i class="icon-trash"></i></div>
                    <div>
                        <div class="SnModal-wrapper" data-modal="invoiceItemModal${uniqueId}">
                            <div class="SnModal">
                                <div class="SnModal-close" data-modalclose="invoiceItemModal${uniqueId}"
                                    onclick="Invoice.closeItemModal(\'${uniqueId}\')">
                                    <svg viewBox="64 64 896 896" class="" data-icon="close" width="1em" height="1em"
                                        fill="currentColor" aria-hidden="true" focusable="false">
                                        <path
                                            d="M563.8 512l262.5-312.9c4.4-5.2.7-13.1-6.1-13.1h-79.8c-4.7 0-9.2 2.1-12.3 5.7L511.6 449.8 295.1 191.7c-3-3.6-7.5-5.7-12.3-5.7H203c-6.8 0-10.5 7.9-6.1 13.1L459.4 512 196.9 824.9A7.95 7.95 0 0 0 203 838h79.8c4.7 0 9.2-2.1 12.3-5.7l216.5-258.1 216.5 258.1c3 3.6 7.5 5.7 12.3 5.7h79.8c6.8 0 10.5-7.9 6.1-13.1L563.8 512z">
                                        </path>
                                    </svg></div>
                                <div class="SnModal-header">Seleccionar un Producto/Servicio</div>
                                <div class="SnModal-body">
                                    <div class="SnForm-item">
                                        <label class="SnForm-label" for="invoiceItemProductSearch${uniqueId}">Aquí puedes buscar y seleccionar tu producto/servicio!</label>
                                        <div class="SnControl-wrapper">
                                            <input class="SnForm-control SnControl" type="text" id="invoiceItemProductSearch${uniqueId}">
                                            <i class="icon-search SnControl-suffix"></i>
                                        </div>
                                    </div>
                                    <div class="SnCollapse" data-collapse="invoiceProductData${uniqueId}">
                                        <div class="SnGrid m-grid-3">
                                            <div class="SnForm-item">
                                                <label class="SnForm-label" for="invoiceItemAffectationCode${uniqueId}">Tipo Afectación IGV</label>
                                                <select name="invoice[item][${uniqueId}][affectationCode]" id="invoiceItemAffectationCode${uniqueId}" class="SnForm-control jsInvoiceItemAffectationCode">
                                                    ' . $affectationIgvTemplate . '
                                                </select>
                                            </div>
                                            <div class="SnForm-item">
                                                <label class="SnForm-label" for="invoiceItemUnitMeasure${uniqueId}">Und/Medida</label>
                                                <select name="invoice[item][${uniqueId}][unitMeasure]" id="invoiceItemUnitMeasure${uniqueId}" class="SnForm-control">
                                                    ' . $unitMeasureTemplate . '
                                                </select>
                                            </div>
                                            <div class="SnForm-item">
                                                <label class="SnForm-label" for="invoiceItemQuantity${uniqueId}">Cantidad</label>
                                                <div class="SnControl-wrapper">
                                                    <i class="icon-world SnControl-prefix"></i>
                                                    <input class="SnForm-control SnControl" type="number" step="any" min="0"
                                                        id="invoiceItemQuantity${uniqueId}" name="invoice[item][${uniqueId}][quantity]">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="SnForm-item">
                                            <label class="SnForm-label" for="invoiceItemDescription${uniqueId}">Descripcion</label>
                                            <div class="SnControl-wrapper">
                                                <i class="icon-file-text SnControl-prefix"></i>
                                                <input class="SnForm-control SnControl" type="text"
                                                    id="invoiceItemDescription${uniqueId}"
                                                    name="invoice[item][${uniqueId}][descripcion]">
                                            </div>
                                        </div>
                                        <div class="SnGrid m-grid-3">
                                            <div class="SnForm-item">
                                                <label class="SnForm-label" for="invoiceItemUnitPrice${uniqueId}">Precio/Uni(Inc.IGV)</label>
                                                <div class="SnControl-wrapper">
                                                    <span class="jsCurrencySymbol SnControl-prefix"></span>
                                                    <input class="SnForm-control SnControl" type="number" step="any"
                                                        id="invoiceItemUnitPrice${uniqueId}"
                                                        name="invoice[item][${uniqueId}][unitPrice]">
                                                </div>
                                            </div>
                                            <div class="SnForm-item">
                                                <label class="SnForm-label" for="invoiceItemUnitValue${uniqueId}">Precio/Uni(Sin.IGV)</label>
                                                <div class="SnControl-wrapper">
                                                    <i class="jsCurrencySymbol SnControl-prefix"></i>
                                                    <input class="SnForm-control SnControl" type="number" step="any"
                                                        id="invoiceItemUnitValue${uniqueId}"
                                                        name="invoice[item][${uniqueId}][unitValue]">
                                                </div>
                                            </div>
                                            <div class="SnForm-item">
                                                <label class="SnForm-label" for="invoiceItemTotalValue${uniqueId}">Sub.Total</label>
                                                <div class="SnControl-wrapper">
                                                    <span class="jsCurrencySymbol SnControl-prefix"></span>
                                                    <input class="SnForm-control SnControl jsInvoiceItemTotalValue" type="number" step="any"
                                                        id="invoiceItemTotalValue${uniqueId}"
                                                        name="invoice[item][${uniqueId}][totalValue]">
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
                                                    <input class="SnForm-control SnControl jsInvoiceItemIsc" type="number" step="any" disabled
                                                        id="invoiceItemIsc${uniqueId}" name="invoice[item][${uniqueId}][isc]">
                                                </div>
                                                <input type="hidden" id="invoiceItemTotalBaseIsc${uniqueId}" name="invoice[item][${uniqueId}][totalBaseIsc]">
                                            </div>
                                        </div>
                                        <div class="SnGrid m-grid-3">
                                            <div class="SnForm-item">
                                                <label class="SnForm-label" for="invoiceItemDiscount${uniqueId}">Descuento</label>
                                                <div class="SnControl-wrapper">
                                                    <span class="jsCurrencySymbol SnControl-prefix"></span>
                                                    <input class="SnForm-control SnControl jsInvoiceItemDiscount" type="number" step="any" min="0"
                                                        id="invoiceItemDiscount${uniqueId}" name="invoice[item][${uniqueId}][discount]">
                                                </div>
                                            </div>
                                            <div class="SnForm-item">
                                                <label class="SnForm-label" for="invoiceItemIgv${uniqueId}">IGV (18%)</label>
                                                <div class="SnControl-wrapper">
                                                    <span class="jsCurrencySymbol SnControl-prefix"></span>
                                                    <input class="SnForm-control SnControl jsInvoiceItemIgv" type="number" step="any" disabled
                                                        id="invoiceItemIgv${uniqueId}" name="invoice[item][${uniqueId}][igv]">
                                                </div>
                                                <input type="hidden" id="invoiceItemTotalBaseIgv${uniqueId}" name="invoice[item][${uniqueId}][totalBaseIgv]">
                                            </div>
                                            <div class="SnForm-item">
                                                <label class="SnForm-label" for="invoiceItemTotal${uniqueId}">Total</label>
                                                <div class="SnControl-wrapper">
                                                    <span class="jsCurrencySymbol SnControl-prefix"></span>
                                                    <input class="SnForm-control SnControl jsInvoiceItemTotal" type="number" step="any" disabled
                                                        id="invoiceItemTotal${uniqueId}" name="invoice[item][${uniqueId}][total]">
                                                </div>
                                                <input type="hidden" id="invoiceItemTotalDecimal${uniqueId}">
                                            </div>
                                        </div>
                                        <div class="SnBtn primary block" onclick="Invoice.closeItemModal(\'${uniqueId}\')">Aceptar</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>';
    }
}
