<?php require_once __DIR__ . '/layout/header.php'; ?>
    <div class="SnContent">
        <div class="SnToolbar">
            <div class="SnToolbar-left">
                <i class="icon-equalizer SnMr-2"></i> <strong>CATEGORIAS</strong>
            </div>
            <div class="SnToolbar-right">
                <div class="SnBtn jsCategoryAction" onclick="CategoryToPrint()">
                    <i class="icon-printer"></i>
                </div>
                <div class="SnBtn jsCategoryAction" onclick="CategoryToExcel()">
                    <i class="icon-file-excel"></i>
                </div>
                <div class="SnBtn jsCategoryAction" onclick="CategoryList()">
                    <i class="icon-reload-alt"></i>
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