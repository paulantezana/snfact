<?php require_once __DIR__ . '/layout/header.php' ?>

<div class="SnContent">
    <?php require_once  __DIR__ . '/partials/invoiceToolbar.php'; ?>
    <?php var_dump($_SESSION); ?>
</div>

<script src="<?= URL_PATH ?>/assets/dist/script/dashboard-min.js"></script>

<?php require_once __DIR__ . '/layout/footer.php' ?>