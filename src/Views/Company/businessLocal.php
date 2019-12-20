<?php require_once __DIR__ . '/layout/header.php'; ?>
    <div class="SnContent">
        <div class="SnToolbar">
            <div class="SnToolbar-left">
                <i class="icon-dots SnMr-2"></i> Sucursales
            </div>
            <div class="SnToolbar-right">
                <div class="SnBtn jsBusinessLocalAction SnMr-2" onclick="BusinessLocalList()">
                    <i class="icon-reload-alt SnMr-2"></i> Actualizar
                </div>
                <div class="SnBtn primary jsBusinessLocalAction" onclick="BusinessLocalShowModalCreate()">
                    <i class="icon-plus2 SnMr-2"></i> Nuevo
                </div>
            </div>
        </div>
        <div class="SnCard">
            <div class="SnCard-body">
                <div class="SnControl-wrapper SnMb-5">
                    <input type="text" class="SnForm-control" id="searchContent">
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
                <svg viewBox="64 64 896 896" class="" data-icon="close" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false">
                    <path d="M563.8 512l262.5-312.9c4.4-5.2.7-13.1-6.1-13.1h-79.8c-4.7 0-9.2 2.1-12.3 5.7L511.6 449.8 295.1 191.7c-3-3.6-7.5-5.7-12.3-5.7H203c-6.8 0-10.5 7.9-6.1 13.1L459.4 512 196.9 824.9A7.95 7.95 0 0 0 203 838h79.8c4.7 0 9.2-2.1 12.3-5.7l216.5-258.1 216.5 258.1c3 3.6 7.5 5.7 12.3 5.7h79.8c6.8 0 10.5-7.9 6.1-13.1L563.8 512z"></path>
                </svg>
            </div>
            <div class="SnModal-header">Sucursal</div>
            <div class="SnModal-body">
                <form action="" class="SnForm" id="businessLocalForm" onsubmit="BusinessLocalSubmit(event)">
                    <input type="hidden" class="SnForm-control" id="businessLocalId" name="businessLocal[id]">
                    <div class="SnGrid m-grid-2 l-grid-3">
                        <div class="SnForm-item required">
                            <label for="businessLocalSunatCode" class="SnForm-label">Código SUNAT</label>
                            <div class="SnControl-wrapper">
                                <i class="icon-package SnControl-prefix"></i>
                                <input class="SnForm-control SnControl" type="text" id="businessLocalSunatCode" placeholder="Código SUNAT" name="businessLocal[sunatCode]">
                            </div>
                        </div>
                        <div class="SnForm-item required">
                            <label for="businessLocalShortName" class="SnForm-label">Nombre de Sucursal</label>
                            <div class="SnControl-wrapper">
                                <i class="icon-package SnControl-prefix"></i>
                                <input class="SnForm-control SnControl" type="text" id="businessLocalShortName" placeholder="Nombre de Sucursal" name="businessLocal[shortName]">
                            </div>
                        </div>
                        <div class="SnForm-item">
                            <label for="businessLocalLocationCode" class="SnForm-label">Ubigeo</label>
                            <div class="SnControl-wrapper">
                                <i class="icon-package SnControl-prefix"></i>
                                <input class="SnForm-control SnControl" type="text" id="businessLocalLocationCode" placeholder="Ubigeo" name="businessLocal[locationCode]">
                            </div>
                        </div>
                        <div class="SnForm-item">
                            <label for="businessLocalAddress" class="SnForm-label">Dirección</label>
                            <div class="SnControl-wrapper">
                                <i class="icon-package SnControl-prefix"></i>
                                <input class="SnForm-control SnControl" type="text" id="businessLocalAddress" placeholder="Dirección" name="businessLocal[address]">
                            </div>
                        </div>
                        <div class="SnForm-item">
                            <label for="businessLocalDescription" class="SnForm-label">Información Adicional</label>
                            <div class="SnControl-wrapper">
                                <i class="icon-package SnControl-prefix"></i>
                                <input class="SnForm-control SnControl" type="text" id="businessLocalDescription" placeholder="Información Adicional" name="businessLocal[description]">
                            </div>
                        </div>
                        <div class="SnForm-item">
                            <label for="businessLocalPdfInvoiceSize" class="SnForm-label">PDF formato</label>
                            <select id="businessLocalPdfInvoiceSize" class="SnForm-control" name="businessLocal[pdfInvoiceSize]">
                                <option value="A4">A4</option>
                                <option value="A5">A5</option>
                                <option value="TICKET">TICKET</option>
                            </select>
                        </div>
                        <div class="SnForm-item">
                            <label for="businessLocalPdfHeader" class="SnForm-label">PDF header</label>
                            <div class="SnControl-wrapper">
                                <i class="icon-package SnControl-prefix"></i>
                                <input class="SnForm-control SnControl" type="text" id="businessLocalPdfHeader" placeholder="PDF header" name="businessLocal[pdfHeader]">
                            </div>
                        </div>
                        <div class="SnForm-item">
                            <div class="SnSwitch">
                                <input class="SnSwitch-input" type="checkbox" id="businessLocalState" name="businessLocal[state]">
                                <label class="SnSwitch-label" for="businessLocalState">Estado</label>
                            </div>
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
                            <tfoot>
                                <tr>
                                    <td colspan="3">
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