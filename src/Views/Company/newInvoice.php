<?php require_once __DIR__ . '/layout/header.php'; ?>
<div class="SnContent">
    <div class="">
        <div class="SnCard">
            <div class="SnCard-body" id="invoiceFormTemplateContainer">
                <?= require_once __DIR__ . '/partials/invoiceFormTemplate.php' ?>
            </div>
        </div>
    </div>
</div>
<script src="<?= URL_PATH  ?>/assets/script/company/invoice.js"></script>
<?php require_once __DIR__ . '/layout/footer.php' ?>