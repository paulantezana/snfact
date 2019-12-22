<?php require_once __DIR__ . '/layout/header.php'; ?>
    <div class="SnContent">
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
<?php require_once __DIR__ . '/layout/footer.php' ?>