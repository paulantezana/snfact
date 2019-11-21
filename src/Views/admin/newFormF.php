<?php require_once __DIR__ . '/layout/header.php'; ?>
    <div class="SnContent">
        <?php require_once  __DIR__ . '/partials/invoiceToolbar.php'; ?>
        <div class="SnCard">
            <div class="SnCard-body">
                <form action="">
                    <div class="Invoice">
                        <div class="Invoice-header">
                            <div>
                                <input class="SnSwitch SnSwitch-ios" id="includeIgv" type="checkbox">
                                <label class="SnSwitch-btn" for="includeIgv"></label>
                                <span>Incluye IGV</span>
                            </div>
                            <div>
                                <input class="SnSwitch SnSwitch-ios" id="advancedOptions" type="checkbox">
                                <label class="SnSwitch-btn" for="advancedOptions"></label>
                                <span>Opciones avanzadas</span>
                            </div>
                        </div>
                        <div class="Invoice-body">
                            <div class="SnGrid lg-4" style="grid-row-gap: 0">
                                <div class="SnForm-item required">
                                    <label class="SnForm-label" for="invoiceDocumentCode">Documento</label>
                                    <select class="SnForm-select" name="invoiceDocumentCode" id="invoiceDocumentCode">
                                        <?php foreach ($catDocumentTypeCode as $row): ?>
                                            <option value="<?= $row['code']?>"><?= $row['description']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="SnForm-item required">
                                    <label class="SnForm-label" for="invoiceCurrencyCode">Moneda</label>
                                    <select class="SnForm-select" name="invoiceCurrencyCode" id="invoiceCurrencyCode">
                                        <?php foreach ($catCurrencyTypeCode as $row): ?>
                                            <option value="<?= $row['code']?>"><?= $row['description']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="SnForm-item required">
                                    <label class="SnForm-label" for="invoiceSerie">Serie</label>
                                    <input class="SnForm-input" type="text" name="invoiceDocumentCode" id="invoiceDocumentCode">
                                </div>
                                <div class="SnForm-item required">
                                    <label class="SnForm-label" for="invoiceNumber">Número</label>
                                    <input class="SnForm-input" type="text" name="invoiceDocumentCode" id="invoiceDocumentCode">
                                </div>
                                <div class="SnForm-item required">
                                    <label class="SnForm-label" for="invoiceDateOfIssue">Fecha.Doc:</label>
                                    <input class="SnForm-input" type="date" name="invoiceDocumentCode" id="invoiceDocumentCode">
                                </div>
                                <div class="SnForm-item required">
                                    <label class="SnForm-label" for="invoiceDateOfDue">Fecha.Venc.:</label>
                                    <input class="SnForm-input" type="date" name="invoiceDocumentCode" id="invoiceDocumentCode">
                                </div>
                                <div class="SnForm-item">
                                    <label class="SnForm-label" for="invoiceChangeType">Tipo Cambio (SUNAT):</label>
                                    <input class="SnForm-input" type="text" name="invoiceDocumentCode" id="invoiceDocumentCode">
                                </div>
                                <div class="SnForm-item">
                                    <label class="SnForm-label" for="invoiceVehiclePlate">N° Placa Vehículo:</label>
                                    <input class="SnForm-input" type="text" name="invoiceDocumentCode" id="invoiceDocumentCode">
                                </div>
                                <div class="SnForm-item">
                                    <label class="SnForm-label" for="invoicePurchaseOrder">N° de Orden:</label>
                                    <input class="SnForm-input" type="text" name="invoiceDocumentCode" id="invoiceDocumentCode">
                                </div>
                                <div class="SnForm-item">
                                    <label class="SnForm-label" for="invoiceTerm">Termino:</label>
                                    <input class="SnForm-input" type="text" name="invoiceTerm" id="invoiceTerm">
                                </div>
                            </div>
                            <div class="SnGrid lg-4" style="grid-row-gap: 0">
                                <div class="SnForm-item required">
                                    <label class="SnForm-label" for="invoiceSerie">Tipo Doc.Ident.</label>
                                    <input class="SnForm-input" type="text" name="invoiceDocumentCode" id="invoiceDocumentCode">
                                </div>
                                <div class="SnForm-item required">
                                    <label class="SnForm-label" for="invoiceSerie">N° de R.U.C.:</label>
                                    <input class="SnForm-input" type="text" name="invoiceDocumentCode" id="invoiceDocumentCode">
                                </div>
                                <div class="SnForm-item required">
                                    <label class="SnForm-label" for="invoiceSerie">Razón Social:</label>
                                    <input class="SnForm-input" type="text" name="invoiceDocumentCode" id="invoiceDocumentCode">
                                </div>
                                <div class="SnForm-item required">
                                    <label class="SnForm-label" for="invoiceSerie">Dirección:</label>
                                    <input class="SnForm-input" type="text" name="invoiceDocumentCode" id="invoiceDocumentCode">
                                </div>
                                <div class="SnForm-item required">
                                    <label class="SnForm-label" for="invoiceSerie">Ubigeo:</label>
                                    <input class="SnForm-input" type="text" name="invoiceDocumentCode" id="invoiceDocumentCode">
                                </div>
                                <div class="SnForm-item required">
                                    <label class="SnForm-label" for="invoiceSerie">¿Deseas Enviar el Comprobante Electrónico al Email del Cliente?:</label>
                                    <input class="SnForm-input" type="text" name="invoiceDocumentCode" id="invoiceDocumentCode">
                                </div>
                                <div class="SnForm-item required">
                                    <label class="SnForm-label" for="invoiceSerie">Email:</label>
                                    <input class="SnForm-input" type="text" name="invoiceDocumentCode" id="invoiceDocumentCode">
                                </div>
                            </div>

                            <div class="SnTab">
                                <div class="SnTab-content">
                                    Producto
                                </div>
                                <div class="SnTab-content">
                                    regimen
                                </div>
                                <div class="SnTab-content is-active">
                                    Factura guia
                                </div>
                                <div class="SnTab-content">
                                    Otros doc.
                                </div>
                                <div class="SnTab-content">
                                    anticipo
                                </div>
                                <div class="SnTab-header">
                                    <div class="SnTab-title is-active">
                                        <i class="icon-rocket"></i>
                                        Productos
                                    </div>
                                    <div class="SnTab-title">
                                        <i class="icon-rocket"></i>
                                        Régimen
                                    </div>
                                    <div class="SnTab-title">
                                        <i class="icon-rocket"></i>
                                        Factura guia
                                    </div>
                                    <div class="SnTab-title">
                                        <i class="icon-rocket"></i>
                                        Otros doc.
                                    </div>
                                    <div class="SnTab-title">
                                        <i class="icon-rocket"></i>
                                        Anticipo
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="Invoice-footer">
                            <div class="SnGrid l-2">
                                <div>
                                    <div class="SnForm-item">
                                        <label class="SnForm-label" for="invoiceSerie">Descuento Total (en porcentaje %):</label>
                                        <input class="SnForm-input" type="text" name="invoiceDocumentCode" id="invoiceDocumentCode">
                                    </div>
                                    <div class="SnForm-item">
                                        <label class="SnForm-label" for="invoiceSerie">Otros Cargos:</label>
                                        <input class="SnForm-input" type="text" name="invoiceDocumentCode" id="invoiceDocumentCode">
                                    </div>
                                    <div class="SnForm-item">
                                        <label class="SnForm-label" for="invoiceSerie">Observación:</label>
                                        <textarea class="SnForm-textarea" name="invoiceSerie" id="invoiceSerie" cols="30" rows="5"></textarea>
                                    </div>
                                    <div>
                                        <input class="SnSwitch SnSwitch-ios" id="includeIgv" type="checkbox">
                                        <label class="SnSwitch-btn" for="includeIgv"></label>
                                        <span>¿Bienes Región Selva?</span>
                                    </div>
                                    <div>
                                        <input class="SnSwitch SnSwitch-ios" id="advancedOptions" type="checkbox">
                                        <label class="SnSwitch-btn" for="advancedOptions"></label>
                                        <span>¿Servicios Región Selva?</span>
                                    </div>
                                </div>
                                <div>
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>Descuento Total (-)</td>
                                                <td>S/. 0.00</td>
                                            </tr>
                                            <tr>
                                                <td>Anticipo (-)</td>
                                                <td>S/. 0.00</td>
                                            </tr>
                                            <tr>
                                                <td>Exonerada</td>
                                                <td>S/. 0.00</td>
                                            </tr>
                                            <tr>
                                                <td>Inafecta</td>
                                                <td>S/. 0.00</td>
                                            </tr>
                                            <tr>
                                                <td>Exportación</td>
                                                <td>S/. 0.00</td>
                                            </tr>
                                            <tr>
                                                <td>Gravada</td>
                                                <td>S/. 0.00</td>
                                            </tr>
                                            <tr>
                                                <td>ISC</td>
                                                <td>S/. 0.00</td>
                                            </tr>
                                            <tr>
                                                <td>IGV</td>
                                                <td>S/. 0.00</td>
                                            </tr>
                                            <tr>
                                                <td>Gratuita</td>
                                                <td>S/. 0.00</td>
                                            </tr>
                                            <tr>
                                                <td>ICBPER</td>
                                                <td>S/. 0.00</td>
                                            </tr>
                                            <tr>
                                                <td>Total</td>
                                                <td>S/. 0.00</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div>
                                <button class="SnBtn primary lg block" type="submit">EMITIR</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php require_once __DIR__ . '/layout/footer.php' ?>
