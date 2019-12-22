<?php require_once __DIR__ . '/layout/header.php'; ?>
    <div class="SnContent">
        <div class="SnToolbar">
            <div class="SnToolbar-left">
                <i class="icon-dots SnMr-2"></i> Categorias
            </div>
            <div class="SnToolbar-right">
                <div class="SnBtn jsCategoryAction SnMr-2" onclick="CategoryList()">
                    <i class="icon-reload-alt SnMr-2"></i> Actualizar
                </div>
                <div class="SnBtn primary jsCategoryAction" onclick="CategoryShowModalCreate()">
                    <i class="icon-plus2 SnMr-2"></i> Nuevo
                </div>
            </div>
        </div>
        <div class="SnCard">
            <div class="SnCard-body">
                <div class="SnControl-wrapper SnMb-5">
                    <input type="text" class="SnForm-control SnControl" id="searchContent" placeholder="Buscar...">
                    <span class="SnControl-suffix icon-search4"></span>
                </div>
                <div id="categoryTable"></div>
            </div>
        </div>
    </div>

    <script src="<?= URL_PATH ?>/assets/script/company/category.js"></script>

    <?php require_once __DIR__ . '/partials/categoryModalForm.php' ?>

<?php require_once __DIR__ . '/layout/footer.php' ?>