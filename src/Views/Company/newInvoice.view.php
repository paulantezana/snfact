<div class="SnContent">
    <div>
        <?php require_once __DIR__ . '/partials/invoiceToolBar.php';  ?>
        <div class="SnCard">
            <div class="SnCard-body" id="invoiceFormTemplateContainer">
                <?php require_once __DIR__ . '/partials/invoiceFormTemplate.php' ?>
            </div>
        </div>
    </div>
    <div class="SnModal-wrapper" data-modal="invoiceConfirmModal">
        <div class="SnModal">
            <div class="SnModal-body">
                <div class="SnAlert SnMb-5" id="invoiceConfirmAlert">Se guardó correctamente el documento: F001-17.</div>
                <table class="SnTable InvoiceConfirm-table">
                    <tbody>
                        <tr>
                            <td>
                                <div class="InvoiceConfirm-action">
                                    <i class="icon-notebook position-left"></i>
                                    <div>Imprimir A4</div>
                                </div>
                            </td>
                            <td>
                                <div class="InvoiceConfirm-action">
                                    <i class="icon-notebook position-left"></i>
                                    <div>Enviar a Whatsapp</div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="InvoiceConfirm-action">
                                    <i class="icon-notebook position-left"></i>
                                    <div>Enviar email</div>
                                </div>
                            </td>
                            <td>
                                <a href="<?= URL_PATH ?>/invoice" class="InvoiceConfirm-action">
                                    <i class="icon-notebook position-left"></i>
                                    <div>Listar documentos</div>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="SnGrid s-grid-2">
                    <button type="button" class="SnBtn primary block" onclick="newInvoice()">Realizar otra venta!</button>
                    <a href="<?= URL_PATH ?>/invoice" class="SnBtn block">Ver lista documentos</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= URL_PATH  ?>/assets/script/company/newInvoice.js"></script>