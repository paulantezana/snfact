<div class="SnContent">
    <div class="SnToolbar">
        <div class="SnToolbar-left">
            <i class=" fas fa-list-ul SnMr-2"></i> <strong>PRODUCTOS</strong>
        </div>
        <div class="SnToolbar-right">
            <div class="SnBtn jsProductAction" onclick="ProductToPrint()">
                <i class="fas fa-print"></i>
            </div>
            <div class="SnBtn jsProductAction" onclick="ProductToExcel()">
                <i class="far fa-file-excel"></i>
            </div>
            <div class="SnBtn jsProductAction" onclick="ProductList()">
                <i class="fas fa-sync-alt"></i>
            </div>
            <div class="SnBtn primary jsProductAction" onclick="ProductShowModalCreate()">
                <i class="fas fa-plus SnMr-2"></i> Nuevo
            </div>
        </div>
    </div>
    <div class="SnCard">
        <div class="SnCard-body">
            <div class="SnControl-wrapper SnMb-5">
                <input type="text" class="SnForm-control SnControl" id="productSearch">
                <span class="SnControl-suffix fas fa-search"></span>
            </div>
            <div id="productTable"></div>
        </div>
    </div>
</div>

<script src="<?= URL_PATH ?>/assets/script/company/product.js"></script>

<div class="SnModal-wrapper" data-modal="productModalForm">
    <div class="SnModal">
        <div class="SnModal-close" data-modalclose="productModalForm">
            <i class="fas fa-times"></i>
        </div>
        <div class="SnModal-header"><i class="fas fa-folder-plus SnMr-2"></i> Producto</div>
        <div class="SnModal-body">
            <form action="" class="SnForm" novalidate id="productForm" onsubmit="ProductSubmit(event)">
                <input type="hidden" class="SnForm-control" id="productId">
                <div class="SnGrid s-grid-2">
                    <div class="SnForm-item required">
                        <label for="productProductKey" class="SnForm-label">Código</label>
                        <div class="SnControl-group">
                            <div class="SnControl-wrapper">
                                <i class="fas fa-barcode SnControl-prefix"></i>
                                <input class="SnForm-control SnControl" type="text" id="productProductKey" required>
                            </div>
                            <div class="SnBtn primary"><i class="fas fa-search"></i></div>
                        </div>
                    </div>
                    <div class="SnForm-item required">
                        <label for="productProductCode" class="SnForm-label">Codigo Producto</label>
                        <select id="productProductCode" required></select>
                    </div>
                </div>
                <div class="SnForm-item required">
                    <label for="productDescription" class="SnForm-label">Nombre del Producto o Servicio</label>
                    <div class="SnControl-wrapper">
                        <i class="far fa-sticky-note SnControl-prefix"></i>
                        <input class="SnForm-control SnControl" type="text" id="productDescription" required>
                    </div>
                </div>

                <div class="SnForm-item required">
                    <label for="productAffectationCode" class="SnForm-label">Tipo de IGV</label>
                    <select id="productAffectationCode" class="SnForm-control" required>
                        <?php foreach ($parameter['catAffectationIgvTypeCodes'] ?? [] as $row): ?>
                            <option value="<?= $row['code'] ?>"><?= $row['description'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="SnGrid s-grid-2">
                    <div class="SnForm-item required">
                        <label for="productUnitPrice" class="SnForm-label">PrecioVenta(Inc.IGV)</label>
                        <div class="SnControl-wrapper">
                            <i class="fas fa-coins SnControl-prefix"></i>
                            <input class="SnForm-control SnControl" type="number" step="any" id="productUnitPrice" required>
                        </div>
                    </div>
                    <div class="SnForm-item required">
                        <label for="productUnitValue" class="SnForm-label">PrecioVenta(Sin IGV)</label>
                        <div class="SnControl-wrapper">
                            <i class="fas fa-coins SnControl-prefix"></i>
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
                                <?php foreach ($parameter['categories'] ?? [] as $row): ?>
                                    <option value="<?= $row['category_id'] ?>"><?= $row['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="SnBtn primary" onclick="CategoryShowModalCreate()"><i class="fa fa-plus"></i></div>
                        </div>
                    </div>
                    <div class="SnForm-item required">
                        <label for="productUnitMeasureCode" class="SnForm-label">Unidad de Medida</label>
                        <select id="productUnitMeasureCode" class="SnForm-control" required>
                            <option value="">Seleccionar</option>
                            <?php foreach ($parameter['catUnitMeasureTypeCodes'] ?? [] as $row): ?>
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
                            <?php foreach ($parameter['catSystemIscTypeCodes'] ?? [] as $row): ?>
                                <option value="<?= $row['code'] ?>"><?= $row['description'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="SnForm-item">
                        <label for="productIsc" class="SnForm-label">ISC</label>
                        <input type="number" step="any" class="SnForm-control" id="productIsc">
                    </div>
                </div>
                <div class="SnForm-item SnGrid s-grid-2">
                    <div class="SnSwitch">
                        <input class="SnSwitch-control" type="checkbox" id="productState">
                        <label class="SnSwitch-label" for="productState">Estado</label>
                    </div>
                    <div class="SnSwitch">
                        <input class="SnSwitch-control" type="checkbox" id="productBagTax">
                        <label class="SnSwitch-label" for="productBagTax">¿Es Afecto al ICBPER?</label>
                    </div>
                </div>
                <button type="submit" class="SnBtn primary block" id="productFormSubmit">Guardar</button>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/categoryModalForm.php' ?>
