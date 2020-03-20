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

    <div class="SnCard SnMb-5">
      <div class="SnCard-body">
        <div class="InvoiceTableLegend">
          <div class="InvoiceTableLegend-item"><img src="<?= URL_PATH?>/assets/images/sunatLogo.png" alt="sunat" style="height: 32px"></div>
          <div class="InvoiceTableLegend-item"><i class="fas fa-check SnMr-2" style="color: var(--snSuccess)"></i>Aceptado</div>
          <div class="InvoiceTableLegend-item"><i class="fas fa-sync-alt SnMr-2" style="color: var(--snWarning)"></i>Pendiente de Envío</div>
          <div class="InvoiceTableLegend-item"><i class="fas fa-chevron-circle-right SnMr-2" style="color: var(--snColor1)"></i>Para resumen</div>
          <div class="InvoiceTableLegend-item"><i class="fas fa-ban SnMr-2" style="color: var(--snError)"></i>Comunicación de Baja (Anulado)</div>
        </div>
      </div>
    </div>

    <div class="SnCard">
        <div class="SnCard-body">
            <div class="SnGrid m-grid-4 InvoiceTableFilter">
                <div class="SnForm-item">
                    <select class="SnForm-control" id="filterDocumentCode" onchange="invoiceFilter()">
                      <option value="">Comprobante</option>
                      <?php foreach ($parameter['catDocumentTypeCode'] as $row) : ?>
                        <option value="<?= $row['code'] ?>"><?= $row['description'] ?></option>
                      <?php endforeach; ?>
                    </select>
                </div>
                <div class="SnForm-item">
                    <select class="SnForm-control" id="filterInvoiceId" onchange="invoiceFilter()">
                      <option value="">Serie / Numero</option>
                    </select>
                </div>
                <div class="SnForm-item">
                    <input type="date" id="filterStartDate" class="SnForm-control" onchange="invoiceFilter()">
                </div>
                <div class="SnForm-item">
                    <input type="date" id="filterEndDate" class="SnForm-control" onchange="invoiceFilter()">
                </div>
            </div>
            <div id="invoiceTable"></div>
        </div>
    </div>
</div>

<script src="<?= URL_PATH  ?>/assets/script/company/invoice.js"></script>

<?php require_once __DIR__ . '/partials/invoiceDocumentModal.php' ?>
<?php require_once __DIR__ . '/partials/invoiceModalSendEmail.php' ?>
