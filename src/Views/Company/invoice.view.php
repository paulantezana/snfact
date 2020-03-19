<div class="SnContent">
    <div class="SnToolbar" id="InvoiceToolbar">
        <div class="SnToolbar-left">
            <i class="fas fa-chevron-right SnMr-2"></i>COMPROBANTES
        </div>
        <div class="SnToolbar-right">
            <div class="SnBtn jsCategoryAction" onclick="invoiceList()">
              <i class="fas fa-sync-alt"></i>
            </div>
            <div class="SnDropdown">
                <div class="SnDropdown-toggle SnBtn primary"><i class="fas fa-plus SnMr-2"></i>Nuevo</div>
                <ul class="SnDropdown-list">
                    <li class="SnDropdown-item"><a href="<?= URL_PATH ?>/invoice/newInvoice?documentCode=01">Emitir factura</a></li>
                    <li class="SnDropdown-item"><a href="<?= URL_PATH ?>/invoice/newInvoice?documentCode=03">Emitir boleta</a></li>
                    <li class="SnDropdown-item"><a href="<?= URL_PATH ?>/invoice/newInvoice?documentCode=07">Emitir nota crédito</a></li>
                    <li class="SnDropdown-item"><a href="<?= URL_PATH ?>/invoice/newInvoice?documentCode=08">Emitir nota débito</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="SnCard">
        <div class="SnCard-body">
            <div class="SnGrid m-grid-3">
                <div class="SnForm-item">
                    <label for="filterStartDate" class="SnForm-label">Fecha inicio</label>
                    <input type="date" id="filterStartDate" class="SnForm-control">
                </div>
                <div class="SnForm-item">
                    <label for="filterEndDate" class="SnForm-label">Fecha limite</label>
                    <input type="date" id="filterEndDate" class="SnForm-control">
                </div>
                <div class="SnForm-item">
                    <label for="filterCustomerId" class="SnForm-label">Cliente</label>
                    <select id="filterCustomerId" class="SnForm-control"></select>
                </div>
            </div>
            <div id="invoiceTable"></div>
        </div>
    </div>
</div>

<script src="<?= URL_PATH  ?>/assets/script/company/invoice.js"></script>

<?php require_once __DIR__ . '/partials/invoiceDocumentModal.php' ?>
<?php require_once __DIR__ . '/partials/invoiceModalSendEmail.php' ?>
