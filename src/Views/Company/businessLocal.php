<?php require_once __DIR__ . '/layout/header.php'; ?>
    <div class="SnContent">
        <div class="SnToolbar">
            <div class="SnToolbar-left">
                <i class="icon-equalizer SnMr-2"></i> LOCALES
            </div>
            <div class="SnToolbar-right">
                <div class="SnBtn jsBusinessLocalAction" onclick="BusinessLocalList()">
                    <i class="icon-reload-alt"></i>
                </div>
                <div class="SnBtn primary jsBusinessLocalAction" onclick="BusinessLocalShowModalCreate()">
                    <i class="icon-plus2 SnMr-2"></i> Nuevo
                </div>
            </div>
        </div>
        
        <div class="SnCard">
            <div class="SnCard-body">
                <div class="SnControl-wrapper SnMb-5">
                    <input type="text" class="SnForm-control" id="searchContent" placeholder="Buscar...">
                    <i class="SnControl-suffix icon-search4"></i>
                </div>
                <div id="businessLocalTable"></div>
            </div>
        </div>
    </div>

    <script src="<?= URL_PATH ?>/assets/script/company/businessLocal.js"></script>

    <div class="SnModal-wrapper" data-modal="businessLocalModalForm">
        <div class="SnModal" style="max-width: 90vw; top: 50px;">
            <div class="SnModal-close" data-modalclose="businessLocalModalForm">
                <i class="icon-cross"></i>
            </div>
            <div class="SnModal-header"><i class="icon-file-plus SnMr-2"></i> Sucursal</div>
            <div class="SnModal-body">
                <form action="" class="SnForm" novalidate id="businessLocalForm" onsubmit="BusinessLocalSubmit(event)">
                    <input type="hidden" class="SnForm-control" id="businessLocalId">
                    <div class="SnGrid m-grid-2 l-grid-3">
                        <div class="SnForm-item required">
                            <label for="businessLocalSunatCode" class="SnForm-label">Código SUNAT</label>
                            <div class="SnControl-wrapper">
                                <i class="icon-barcode2 SnControl-prefix"></i>
                                <input class="SnForm-control SnControl" type="text" id="businessLocalSunatCode" placeholder="Código SUNAT" required>
                            </div>
                        </div>
                        <div class="SnForm-item required">
                            <label for="businessLocalShortName" class="SnForm-label">Nombre de Sucursal</label>
                            <div class="SnControl-wrapper">
                                <i class="icon-vcard SnControl-prefix"></i>
                                <input class="SnForm-control SnControl" type="text" id="businessLocalShortName" placeholder="Nombre de Sucursal" required>
                            </div>
                        </div>
                        <div class="SnForm-item">
                            <label for="businessLocalLocationCode" class="SnForm-label">Ubigeo</label>
                            <div class="SnControl-wrapper">
                                <i class="icon-sphere SnControl-prefix"></i>
                                <input class="SnForm-control SnControl" type="text" id="businessLocalLocationCode" placeholder="Ubigeo">
                            </div>
                        </div>
                        <div class="SnForm-item">
                            <label for="businessLocalAddress" class="SnForm-label">Dirección</label>
                            <div class="SnControl-wrapper">
                                <i class="icon-home2 SnControl-prefix"></i>
                                <input class="SnForm-control SnControl" type="text" id="businessLocalAddress" placeholder="Dirección">
                            </div>
                        </div>
                        <div class="SnForm-item">
                            <label for="businessLocalDescription" class="SnForm-label">Información Adicional</label>
                            <div class="SnControl-wrapper">
                                <i class="icon-file-text2 SnControl-prefix"></i>
                                <input class="SnForm-control SnControl" type="text" id="businessLocalDescription" placeholder="Información Adicional">
                            </div>
                        </div>
                        <div class="SnForm-item">
                            <label for="businessLocalPdfInvoiceSize" class="SnForm-label">PDF formato</label>
                            <select id="businessLocalPdfInvoiceSize" class="SnForm-control">
                                <option value="A4">A4</option>
                                <option value="A5">A5</option>
                                <option value="TICKET">TICKET</option>
                            </select>
                        </div>
                        <div class="SnForm-item">
                            <label for="businessLocalPdfHeader" class="SnForm-label">PDF header</label>
                            <div class="SnControl-wrapper">
                                <i class="icon-file-pdf SnControl-prefix"></i>
                                <input class="SnForm-control SnControl" type="text" id="businessLocalPdfHeader" placeholder="PDF header">
                            </div>
                        </div>
                        <div class="SnForm-item">
                            <div class="SnSwitch">
                                <input class="SnSwitch-input" type="checkbox" id="businessLocalState">
                                <label class="SnSwitch-label" for="businessLocalState">Estado</label>
                            </div>
                        </div>
                    </div>
                    <div class="SnTable-wrapper SnMb-4">
                        <table class="SnTable">
                            <thead>
                                <tr>
                                    <th>Documento</th>
                                    <th style="width: 150px">Serie</th>
                                    <th style="width: 100px">Contingencia</th>
                                    <th style="width: 50px"></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <td colspan="4">
                                        <div class="SnBtn block" data-itemtemplate="<?php echo htmlspecialchars(($itemTemplate ?? ''),ENT_QUOTES) ?>" onclick="BusinessLocalSerieAddItem(0,'','')" id="businessLocalAddItem">Agregar serie</div>
                                    </td>
                                </tr>
                            </tfoot>
                            <tbody id="businessLocalSeriesTableBody">
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" class="SnBtn primary block" id="businessLocalFormSubmit">Guardar</button>
                </form>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/layout/footer.php' ?>