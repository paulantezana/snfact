<?php require_once __DIR__ . '/layout/header.php'; ?>
    <div class="SnContent">
        <div class="SnToolbar">
            <div class="SnToolbar-left">
                <i class=" icon-equalizer SnMr-2"></i> <strong>PRODUCTOS</strong>
            </div>
            <div class="SnToolbar-right">
                <div class="SnBtn jsProductAction" onclick="ProductToPrint()">
                    <i class="icon-printer"></i>
                </div>
                <div class="SnBtn jsProductAction" onclick="ProductToExcel()">
                    <i class="icon-file-excel"></i>
                </div>
                <div class="SnBtn jsProductAction" onclick="ProductList()">
                    <i class="icon-reload-alt"></i>
                </div>
                <div class="SnBtn primary jsProductAction" onclick="ProductShowModalCreate()">
                    <i class="icon-plus2 SnMr-2"></i> Nuevo
                </div>
            </div>
        </div>
        <div class="SnCard">
            <div class="SnCard-body">
                <div class="SnControl-wrapper SnMb-5">
                    <input type="text" class="SnForm-control SnControl" id="productSearch">
                    <span class="SnControl-suffix icon-search4"></span>
                </div>
                <div id="productTable"></div>
            </div>
        </div>
    </div>

    <script src="<?= URL_PATH ?>/assets/script/company/product.js"></script>

    <div class="SnModal-wrapper" data-modal="productModalForm">
        <div class="SnModal">
            <div class="SnModal-close" data-modalclose="productModalForm">
                <i class="icon-cross"></i>
            </div>
            <div class="SnModal-header"><i class="icon-file-plus SnMr-2"></i> Producto</div>
            <div class="SnModal-body">
                <form action="" class="SnForm" novalidate id="productForm" onsubmit="ProductSubmit(event)">
                    <input type="hidden" class="SnForm-control" id="productId">
                    <div class="SnGrid s-grid-2">
                        <div class="SnForm-item required">
                            <label for="productProductKey" class="SnForm-label">Código</label>
                            <div class="SnControl-group">
                                <div class="SnControl-wrapper">
                                    <i class="icon-barcode2 SnControl-prefix"></i>
                                    <input class="SnForm-control SnControl" type="text" id="productProductKey" required>
                                </div>
                                <div class="SnBtn primary"><i class="icon-rotate-ccw3"></i></div>
                            </div>
                        </div>
                        <div class="SnForm-item required">
                            <label for="productProductCode" class="SnForm-label">Codigo Producto</label>
                            <select id="productProductCode" required>
                                <option value="">Seleccionar</option>
                            </select>
                        </div>
                    </div>
                    <div class="SnForm-item required">
                        <label for="productDescription" class="SnForm-label">Nombre del Producto o Servicio</label>
                        <div class="SnControl-wrapper">
                            <i class="icon-file-text2 SnControl-prefix"></i>
                            <input class="SnForm-control SnControl" type="text" id="productDescription" required>
                        </div>
                    </div>

                    <div class="SnForm-item required">
                        <label for="productAffectationCode" class="SnForm-label">Tipo de IGV</label>
                        <select id="productAffectationCode" class="SnForm-control" required>
                            <option value="">Seleccionar</option>
                            <?php foreach ($catAffectationIgvTypeCodes ?? [] as $row): ?>
                                <option value="<?= $row['code'] ?>"><?= $row['description'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="SnGrid s-grid-2">
                        <div class="SnForm-item required">
                            <label for="productUnitPrice" class="SnForm-label">PrecioVenta(Inc.IGV)</label>
                            <div class="SnControl-wrapper">
                                <i class="icon-cash4 SnControl-prefix"></i>
                                <input class="SnForm-control SnControl" type="number" step="any" id="productUnitPrice" required>
                            </div>
                        </div>
                        <div class="SnForm-item required">
                            <label for="productUnitValue" class="SnForm-label">PrecioVenta(Sin IGV)</label>
                            <div class="SnControl-wrapper">
                                <i class="icon-cash4 SnControl-prefix"></i>
                                <input class="SnForm-control SnControl" type="number" step="any" id="productUnitValue" required>
                            </div>
                        </div>
                    </div>
                    <div class="SnGrid s-grid-2">
                        <div class="SnForm-item required">
                            <label for="productCategoryId" class="SnForm-label">Categoría</label>
                            <div class="SnControl-group">
                                <select id="productCategoryId" class="SnForm-control" required>
                                    <option value="">Seleccionar</option>
                                    <?php foreach ($categories ?? [] as $row): ?>
                                        <option value="<?= $row['category_id'] ?>"><?= $row['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="SnBtn primary" onclick="CategoryShowModalCreate()"><i class="icon-plus2"></i></div>
                            </div>
                        </div>
                        <div class="SnForm-item required">
                            <label for="productUnitMeasureCode" class="SnForm-label">Unidad de Medida</label>
                            <select id="productUnitMeasureCode" class="SnForm-control" required>
                                <option value="">Seleccionar</option>
                                <?php foreach ($catUnitMeasureTypeCodes ?? [] as $row): ?>
                                    <option value="<?= $row['code'] ?>"><?= $row['description'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="SnGrid s-grid-2">
                        <div class="SnForm-item">
                            <label for="productSystemIscCode" class="SnForm-label">Sistema ISC</label>
                            <select id="productSystemIscCode" class="SnForm-control">
                                <option value="">Seleccionar</option>
                                <?php foreach ($catSystemIscTypeCodes ?? [] as $row): ?>
                                    <option value="<?= $row['code'] ?>"><?= $row['description'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="SnForm-item">
                            <label for="productIsc" class="SnForm-label">ISC</label>
                            <input type="number" step="any" class="SnForm-control" id="productIsc">
                        </div>
                    </div>
                    <div class="SnForm-item">
                        <div class="SnSwitch">
                            <input class="SnSwitch-control" type="checkbox" id="productState">
                            <label class="SnSwitch-label" for="productState">Estado</label>
                        </div>
                    </div>
                    <button type="submit" class="SnBtn primary block" id="productFormSubmit">Guardar</button>
                </form>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . '/partials/categoryModalForm.php' ?>

<?php require_once __DIR__ . '/layout/footer.php' ?>