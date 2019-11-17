<?php require_once __DIR__ . '/layout/header.php'; ?>
    <div class="SnContent">
        <div class="SnToolbar">
            <div class="SnToolbar-left">
                <i class="icon-angle-right"></i> Producto
            </div>
            <div class="SnToolbar-right">
                <div class="SnBtn jsProductOption" onclick="ProductForm.list()">
                    <i class="icon-refresh"></i>
                    Actualizar
                </div>
                <div class="SnBtn primary jsProductOption" onclick="ProductForm.showModalCreate()">
                    <i class="icon-plus"></i>
                    Nuevo
                </div>
            </div>
        </div>
        <div class="SnCard">
            <div class="SnCard-body">
                <div class="SnInput-wrapper SnMb-16">
                    <input type="text" class="SnForm-input" onkeyup="ProductForm.search(event)">
                    <span class="SnInput-suffix icon-search"></span>
                </div>
                <div id="productTable"></div>
            </div>
        </div>
    </div>

    <script src="<?= URL_PATH ?>/assets/dist/script/product-min.js"></script>

    <div class="SnModal-wrapper" data-modal="productModalForm">
        <div class="SnModal">
            <div class="SnModal-close" data-modalclose="productModalForm">
                <svg viewBox="64 64 896 896" class="" data-icon="close" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false">
                    <path d="M563.8 512l262.5-312.9c4.4-5.2.7-13.1-6.1-13.1h-79.8c-4.7 0-9.2 2.1-12.3 5.7L511.6 449.8 295.1 191.7c-3-3.6-7.5-5.7-12.3-5.7H203c-6.8 0-10.5 7.9-6.1 13.1L459.4 512 196.9 824.9A7.95 7.95 0 0 0 203 838h79.8c4.7 0 9.2-2.1 12.3-5.7l216.5-258.1 216.5 258.1c3 3.6 7.5 5.7 12.3 5.7h79.8c6.8 0 10.5-7.9 6.1-13.1L563.8 512z"></path>
                </svg>
            </div>
            <div class="SnModal-header">Producto</div>
            <div class="SnModal-body">
                <form action="" class="SnForm" id="productForm" onsubmit="ProductForm.submit(event)">
                    <input type="hidden" class="SnForm-input" id="productId">
                    <div class="SnGrid s-2">
                        <div class="SnForm-item required">
                            <label for="productProductKey" class="SnForm-label">Código</label>
                            <input type="text" class="SnForm-input" id="productProductKey">
                        </div>
                        <div class="SnForm-item required">
                            <label for="productProductCode" class="SnForm-label">Codigo Producto</label>
                            <select id="productProductCode" class="SnForm-select">
                                <option value="">Seleccionar</option>
                                <?php foreach ($catProductCodes ?? [] as $row): ?>
                                    <option value="<?= $row['code'] ?>"><?= $row['description'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="SnForm-item required">
                        <label for="productDescription" class="SnForm-label">Nombre del Producto o Servicio</label>
                        <input type="text" class="SnForm-input" id="productDescription">
                    </div>

                    <div class="SnForm-item required">
                        <label for="productAffectationCode" class="SnForm-label">Tipo de IGV</label>
                        <select id="productAffectationCode" class="SnForm-select">
                            <option value="">Seleccionar</option>
                            <?php foreach ($catAffectationIgvTypeCodes ?? [] as $row): ?>
                                <option value="<?= $row['code'] ?>"><?= $row['description'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="SnGrid s-2">
                        <div class="SnForm-item required">
                            <label for="productUnitPrice" class="SnForm-label">PrecioVenta(Inc.IGV)</label>
                            <input type="number" step="any" class="SnForm-input" id="productUnitPrice">
                        </div>
                        <div class="SnForm-item required">
                            <label for="productUnitValue" class="SnForm-label">PrecioVenta(Sin IGV)</label>
                            <input type="number" step="any" class="SnForm-input" id="productUnitValue">
                        </div>
                    </div>
                    <div class="SnForm-item required">
                        <label for="productCategoryId" class="SnForm-label">Categoría</label>
                        <select id="productCategoryId" class="SnForm-select">
                            <option value="">Seleccionar</option>
                            <?php foreach ($categories ?? [] as $row): ?>
                                <option value="<?= $row['category_id'] ?>"><?= $row['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="SnForm-item required">
                        <label for="productUnitMeasureCode" class="SnForm-label">Unidad de Medida</label>
                        <select id="productUnitMeasureCode" class="SnForm-select">
                            <option value="">Seleccionar</option>
                            <?php foreach ($catUnitMeasureTypeCodes ?? [] as $row): ?>
                                <option value="<?= $row['code'] ?>"><?= $row['description'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="SnGrid s-2">
                        <div class="SnForm-item">
                            <label for="productSystemIscCode" class="SnForm-label">Sistema ISC</label>
                            <select id="productSystemIscCode" class="SnForm-select">
                                <option value="">Seleccionar</option>
                                <?php foreach ($catSystemIscTypeCodes ?? [] as $row): ?>
                                    <option value="<?= $row['code'] ?>"><?= $row['description'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="SnForm-item">
                            <label for="productIsc" class="SnForm-label">ISC</label>
                            <input type="number" step="any" class="SnForm-input" id="productIsc">
                        </div>
                    </div>
                    <div class="SnForm-item">
                        <p>Estado</p>
                        <input class="SnSwitch SnSwitch-ios" id="productState" type="checkbox">
                        <label class="SnSwitch-btn" for="productState"></label>
                    </div>
                    <div class="SnForm-item">
                        <button type="submit" class="SnBtn primary block" id="productFormSubmit">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
require_once __DIR__ . '/layout/footer.php'
?>