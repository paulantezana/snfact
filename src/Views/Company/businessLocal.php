<?php require_once __DIR__ . '/layout/header.php'; ?>
    <div class="SnContent">
        <div class="SnToolbar">
            <div class="SnToolbar-left">
                <i class="icon-angle-right"></i> Sucursales
            </div>
            <div class="SnToolbar-right">
                <div class="SnBtn jsBusinessLocalOption" onclick="BusinessLocalForm.list()">
                    <i class="icon-refresh"></i>
                    Actualizar
                </div>
                <div class="SnBtn primary jsBusinessLocalOption" onclick="BusinessLocalForm.showModalCreate()">
                    <i class="icon-plus"></i>
                    Nuevo
                </div>
            </div>
        </div>
        <div class="SnCard">
            <div class="SnCard-body">
                <div class="SnInput-wrapper SnMb-16">
                    <input type="text" class="SnForm-control" onkeyup="BusinessLocalForm.search(event)">
                    <span class="SnInput-suffix icon-search"></span>
                </div>
                <div id="businessLocalTable"></div>
            </div>
        </div>
    </div>

    <script src="<?= URL_PATH ?>/assets/script/company/businessLocal.js"></script>

    <div class="SnModal-wrapper" data-modal="businessLocalModalForm">
        <div class="SnModal" style="max-width: 90vw; top: 50px;">
            <div class="SnModal-close" data-modalclose="businessLocalModalForm">
                <svg viewBox="64 64 896 896" class="" data-icon="close" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false">
                    <path d="M563.8 512l262.5-312.9c4.4-5.2.7-13.1-6.1-13.1h-79.8c-4.7 0-9.2 2.1-12.3 5.7L511.6 449.8 295.1 191.7c-3-3.6-7.5-5.7-12.3-5.7H203c-6.8 0-10.5 7.9-6.1 13.1L459.4 512 196.9 824.9A7.95 7.95 0 0 0 203 838h79.8c4.7 0 9.2-2.1 12.3-5.7l216.5-258.1 216.5 258.1c3 3.6 7.5 5.7 12.3 5.7h79.8c6.8 0 10.5-7.9 6.1-13.1L563.8 512z"></path>
                </svg>
            </div>
            <div class="SnModal-header">Sucursal</div>
            <div class="SnModal-body">
                <form action="" class="SnForm" id="businessLocalForm" onsubmit="BusinessLocalForm.submit(event)">
                    <input type="hidden" class="SnForm-control" id="businessLocalId">
                    <div class="SnGrid m-2 l-3">
                        <div class="SnForm-item required">
                            <label for="businessLocalSunatCode" class="SnForm-label">Código SUNAT</label>
                            <input type="text" class="SnForm-control" id="businessLocalSunatCode" name="businessLocalSunatCode">
                        </div>
                        <div class="SnForm-item">
                            <label for="businessLocalSubsidiary" class="SnForm-label">Nombre de Sucursal</label>
                            <input type="text" class="SnForm-control" id="businessLocalSubsidiary" name="businessLocalSubsidiary">
                        </div>
                        <div class="SnForm-item">
                            <label for="businessLocalLocation" class="SnForm-label">Ubigeo</label>
                            <input type="text" class="SnForm-control" id="businessLocalLocation" name="businessLocalLocation">
                        </div>
                        <div class="SnForm-item">
                            <label for="businessLocalAddress" class="SnForm-label">Dirección</label>
                            <input type="text" class="SnForm-control" id="businessLocalAddress" name="businessLocalAddress">
                        </div>
                        <div class="SnForm-item">
                            <label for="businessLocalOptionalInfo" class="SnForm-label">Información Adicional</label>
                            <input type="text" class="SnForm-control" id="businessLocalOptionalInfo" name="businessLocalOptionalInfo">
                        </div>
                        <div class="SnForm-item">
                            <label for="businessLocalPdfInvoiceSize" class="SnForm-label">pdf_invoice_size</label>
                            <input type="text" class="SnForm-control" id="businessLocalPdfInvoiceSize" name="businessLocalPdfInvoiceSize">
                        </div>
                        <div class="SnForm-item">
                            <label for="businessLocalPdfHeader" class="SnForm-label">pdf_header</label>
                            <input type="text" class="SnForm-control" id="businessLocalPdfHeader" name="businessLocalPdfHeader">
                        </div>
                        <div class="SnForm-item">
                            <p>Estado</p>
                            <input class="SnSwitch SnSwitch-ios" id="businessLocalState" type="checkbox" name="businessLocalState">
                            <label class="SnSwitch-btn" for="businessLocalState"></label>
                        </div>
                    </div>

                    <div class="SnTable-wrapper">
                        <table class="SnTable">
                            <thead>
                                <tr>
                                    <th>Documento</th>
                                    <th>Serie</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="businessLocalSeriesTableBody">
                            <?php foreach ($catDocumentTypeCode ?? [] as $key => $row): ?>
                                <tr id="businessLocalItem<?= $key ?>" data-uniqueId="<?= $key ?>">
                                    <td>
                                        <select class="SnForm-select" id="documentCode<?= $key ?>" name="item<?= $key ?>DocumentCode" required>
                                            <?php foreach ($catDocumentTypeCode as $keyOpt => $rowOpt): ?>
                                                <?php if (($row['document_code'] ?? '') == $rowOpt['code']): ?>
                                                    <option value="<?= $rowOpt['code'] ?>" selected><?= $rowOpt['description'] ?></option>
                                                <?php else: ?>
                                                    <option value="<?= $rowOpt['code'] ?>"><?= $rowOpt['description'] ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>
                                        <input type="hidden" name="item<?= $key ?>BusinessSerieId" value="<?= isset($row['business_serie_id']) ? $row['business_serie_id'] : 0 ?>">
                                    </td>
                                    <td>
                                        <input type="text" class="SnForm-control" id="serie<?= $key ?>"
                                               name="item<?= $key ?>Serie" value="<?= isset($row['serie']) ? $row['serie'] : '1' ?>"  required>
                                    </td>
                                    <td>
                                        <button type="button" class="SnBtn" onclick="BusinessLocal.removeItem(<?= $key ?>)">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
<!--                    <div class="btn btn-secondary btn-block btn-sm" data-itemtemplate="--><?php //echo htmlspecialchars(($parameter['itemTemplate'] ?? ''),ENT_QUOTES) ?><!--" onclick="BusinessLocal.addItem()" id="businessLocalAddItem">Agregar serie</div>-->


                    <div class="SnForm-item">
                        <button type="submit" class="SnBtn primary block" id="businessLocalFormSubmit">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
require_once __DIR__ . '/layout/footer.php'
?>