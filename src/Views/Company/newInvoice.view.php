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
                <h2 id="InvoiceConfirmTitle" style="text-align: center"></h2>
                <div class="SnAlert SnMb-5" id="invoiceConfirmAlert"></div>
                <div class="SnGrid s-grid-2 SnMb-5 row-gap InvoiceConfirm">
                    <div class="InvoiceConfirm-action SnBtn" id="InvoiceConfirmPrint">
                        <i class="fas fa-print"></i>
                        <div>Imprimir PDF</div>
                    </div>
                    <a href="#" class="InvoiceConfirm-action SnBtn" target="_blank" id="InvoiceConfirmWhatsapp">
                        <i class="fab fa-whatsapp"></i>
                        <div>Enviar a Whatsapp</div>
                    </a>
                    <div class="InvoiceConfirm-action SnBtn" id="InvoiceConfirmEmail">
                        <i class="fas fa-inbox"></i>
                        <div>Enviar email</div>
                    </div>
                    <a href="<?= URL_PATH ?>/invoice" class="InvoiceConfirm-action SnBtn">
                        <i class="fas fa-list-ul"></i>
                        <div>Listar documentos</div>
                    </a>
                </div>
                <div class="SnGrid s-grid-2">
                    <button type="button" class="SnBtn primary block" onclick="newInvoice()">Realizar otra venta!</button>
                    <a href="<?= URL_PATH ?>/invoice" class="SnBtn block">Ver lista documentos</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= URL_PATH  ?>/assets/script/company/newInvoice.js"></script>

<?php require_once __DIR__ . '/partials/invoiceDocumentModal.php' ?>
<?php require_once __DIR__ . '/partials/invoiceModalSendEmail.php' ?>
