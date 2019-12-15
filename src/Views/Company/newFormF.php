<?php require_once __DIR__ . '/layout/header.php'; ?>
<div class="SnContent">
    <div class="SnContainer">
        <?php require_once __DIR__ . '/partials/invoiceToolbar.php'; ?>
        <div class="SnCard">
            <div class="SnCard-body">
                <form action="" id="invoiceForm" onsubmit="Invoice.submit(event)">
                    <div class="Invoice">
                        <div class="Invoice-header">
                            <div class="SnSwitch">
                                <input class="SnSwitch-input " id="includeIgv" type="checkbox">
                                <label class="SnSwitch-label" for="includeIgv">Incluye IGV</label>
                            </div>
                            <div class="SnSwitch">
                                <input class="SnSwitch-input " id="advancedOptions" type="checkbox" data-collapsetrigger="advancedOpt">
                                <label class="SnSwitch-label" for="advancedOptions">Opciones avanzadas</label>
                            </div>
                        </div>
                        <div class="Invoice-body">
                            <div class="SnCollapse" data-collapse="advancedOpt">
                                <div class="SnGrid m-grid-2 l-grid-3 lg-grid-4 SnMb-32">
                                    <div class="SnForm-item required">
                                        <label class="SnForm-label" for="invoiceDocumentCode">Documento</label>
                                        <select class="SnForm-control" name="invoice[documentCode]" id="invoiceDocumentCode">
                                            <?php foreach ($catDocumentTypeCode as $row) : ?>
                                                <option value="<?= $row['code'] ?>"><?= $row['description'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="SnForm-item required">
                                        <label class="SnForm-label" for="invoiceCurrencyCode">Moneda</label>
                                        <select class="SnForm-control" name="invoice[currencyCode]" id="invoiceCurrencyCode">
                                            <?php foreach ($catCurrencyTypeCode as $row) : ?>
                                                <option value="<?= $row['code'] ?>" data-symbol="<?= $row['symbol'] ?>"><?= $row['description'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="SnForm-item required">
                                        <label class="SnForm-label" for="invoiceSerie">Serie:</label>
                                        <div class="SnControl-wrapper">
                                            <i class="icon-file-text SnControl-prefix"></i>
                                            <input class="SnForm-control SnControl" type="text" name="invoice[serie]" id="invoiceSerie">
                                        </div>
                                    </div>
                                    <div class="SnForm-item required">
                                        <label class="SnForm-label" for="invoiceNumber">Número</label>
                                        <div class="SnControl-wrapper">
                                            <i class="icon-file-text SnControl-prefix"></i>
                                            <input class="SnForm-control SnControl" type="text" name="invoice[number]" id="invoiceNumber" placeholder="#">
                                        </div>
                                    </div>
                                    <div class="SnForm-item required">
                                        <label class="SnForm-label" for="invoiceDateOfIssue">Fecha.Doc:</label>
                                        <input class="SnForm-control" type="date" name="invoice[dateOfIssue]" id="invoiceDateOfIssue">
                                    </div>
                                    <div class="SnForm-item required">
                                        <label class="SnForm-label" for="invoiceDateOfDue">Fecha.Venc.:</label>
                                        <input class="SnForm-control" type="date" name="invoiceDocumentCode" id="invoiceDateOfDue">
                                    </div>
                                    <div class="SnForm-item">
                                        <label class="SnForm-label" for="invoiceChangeType">Tipo Cambio (SUNAT):</label>
                                        <div class="SnControl-wrapper">
                                            <i class="icon-file-text SnControl-prefix"></i>
                                            <input class="SnForm-control SnControl" type="text" name="invoice[changeType]" id="invoiceChangeType">
                                        </div>
                                    </div>
                                    <div class="SnForm-item">
                                        <label class="SnForm-label" for="invoiceVehiclePlate">N° Placa Vehículo:</label>
                                        <div class="SnControl-wrapper">
                                            <i class="icon-file-text SnControl-prefix"></i>
                                            <input class="SnForm-control SnControl" type="text" name="invoice[vehiclePlate]" id="invoiceVehiclePlate" placeholder="Número de placa">
                                        </div>
                                    </div>
                                    <div class="SnForm-item">
                                        <label class="SnForm-label" for="invoicePurchaseOrder">N° de Orden:</label>
                                        <div class="SnControl-wrapper">
                                            <i class="icon-file-text SnControl-prefix"></i>
                                            <input class="SnForm-control SnControl" type="text" name="invoice[purchaseOrder]" id="invoicePurchaseOrder" placeholder="Número de orden">
                                        </div>
                                    </div>
                                    <div class="SnForm-item">
                                        <label class="SnForm-label" for="invoiceTerm">Condiciiones de pago:</label>
                                        <div class="SnControl-wrapper">
                                            <i class="icon-file-text SnControl-prefix"></i>
                                            <input class="SnForm-control SnControl" type="text" name="invoice[term]" id="invoiceTerm" placeholder="Condiciiones de pago">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="SnGrid m-grid-2 l-grid-3 SnMb-32">
                                <div class="SnForm-item required">
                                    <label class="SnForm-label" for="invoiceCustomerDocumentNumber">N° de R.U.C.:</label>
                                    <div class="SnControl-group">
                                        <div class="SnControl-wrapper">
                                            <i class="icon-user SnControl-prefix"></i>
                                            <input class="SnForm-control SnControl" type="text" name="invoice[customer][documentNumber]" id="invoiceCustomerDocumentNumber" placeholder="Número de documento Aquí!">
                                        </div>
                                        <div class="SnBtn primary"><i class="icon-search"></i></div>
                                    </div>
                                </div>
                                <div class="SnForm-item required">
                                    <label class="SnForm-label" for="invoiceCustomerDocumentCode">Tipo Doc.Ident.</label>
                                    <select class="SnForm-control" name="invoice[customer][documentCode]" id="invoiceCustomerDocumentCode">
                                        <?php foreach ($catIdentityDocumentTypeCode as $row) : ?>
                                            <option value="<?= $row['code'] ?>"><?= $row['description'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="SnForm-item required">
                                    <label class="SnForm-label" for="invoiceCustomerSocialReason">Razón Social:</label>
                                    <div class="SnControl-wrapper">
                                        <i class="icon-vcard SnControl-prefix"></i>
                                        <input class="SnForm-control SnControl" type="text" name="invoice[customer][socialReason]" id="invoiceCustomerSocialReason" placeholder="Nombre o Razón Social Aquí">
                                    </div>
                                </div>
                                <div class="SnForm-item l-cols-2 required">
                                    <label class="SnForm-label" for="invoiceCustomerAddress">Dirección:</label>
                                    <div class="SnControl-wrapper">
                                        <i class="icon-home SnControl-prefix"></i>
                                        <input class="SnForm-control SnControl" type="text" name="invoice[customer][address]" id="invoiceCustomerAddress" placeholder="Escribe aquí la dirección completa">
                                    </div>
                                </div>
                                <div class="SnForm-item required">
                                    <label class="SnForm-label" for="invoiceCustomerLocation">Ubigeo:</label>
                                    <div class="SnControl-wrapper">
                                        <i class="icon-world SnControl-prefix"></i>
                                        <input class="SnForm-control SnControl" type="text" name="invoice[customer][location]" id="invoiceCustomerLocation" placeholder="Selecciona Tu Código de Ubigeo">
                                    </div>
                                </div>
                                <div class="SnForm-item SnSwitch l-cols-2 required">
                                    <input class="SnSwitch-input" type="checkbox" name="invoice[customer][sendEmail]" id="invoiceCustomerSendEmail" data-collapsetrigger="sendEmail">
                                    <label class="SnSwitch-label" for="invoiceCustomerSendEmail" >¿Deseas Enviar el Comprobante Electrónico al Email del Cliente?:</label>
                                </div>
                                <div class="SnForm-item required SnCollapse" data-collapse="sendEmail">
                                    <label class="SnForm-label" for="invoiceCustomerEmail">Email:</label>
                                    <div class="SnControl-wrapper">
                                        <i class="icon-email-streamline SnControl-prefix"></i>
                                        <input class="SnForm-control SnControl" type="text" name="invoice[customer][email]" id="invoiceCustomerEmail" placeholder="Escribe aquí el email del cliente">
                                    </div>
                                </div>
                            </div>
                            <div class="SnTab">
                                <div class="SnTab-content">
                                    <div class="SnTable-wrapper">
                                        <table class="SnTable">
                                            <thead>
                                                <tr>
                                                    <th>Descripcion</th>
                                                    <th>Precio</th>
                                                    <th>Cantidad</th>
                                                    <th>Subtotal</th>
                                                    <th>Total</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="7">
                                                        <div class="SnBtn primary block"
                                                             onclick="Invoice.addItem()"
                                                             id="addInvoiceItem"
                                                             data-itemtemplate="<?php echo htmlspecialchars(($invoiceItemTemplate ?? ''),ENT_QUOTES) ?>">
                                                            <i class="icon-plus"></i> Agregar item
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                            <tbody id="invoiceItemTableBody"></tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="SnTab-content">
                                    regimen
                                </div>
                                <div class="SnTab-content">
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
                            <div class="InvoiceFooter">
                                <div class="InvoiceFooter-item">
                                    <div class="SnForm-item">
                                        <label class="SnForm-label" for="invoiceTotalDiscountPercentage">Descuento Total (en porcentaje %):</label>
                                        <input class="SnForm-control" type="number" name="invoiceTotalDiscountPercentage" id="invoiceTotalDiscountPercentage" placeholder="0.00">
                                    </div>
                                    <div class="SnForm-item">
                                        <label class="SnForm-label" for="invoiceTotalOtherCharger">Otros Cargos:</label>
                                        <input class="SnForm-control" type="number" name="invoiceTotalOtherCharger" id="invoiceTotalOtherCharger" placeholder="0.00">
                                    </div>
                                    <div class="SnForm-item">
                                        <label class="SnForm-label" for="invoiceObservation">Observación:</label>
                                        <textarea class="SnForm-control" name="invoiceObservation" id="invoiceObservation" cols="30" rows="7" placeholder="Escribe aquí una observación"></textarea>
                                    </div>
                                    <div class="SnGrid s-grid-2">
                                        <div class="SnSwitch">
                                            <input class="SnSwitch-input" id="service" type="checkbox">
                                            <label class="SnSwitch-label" for="service">¿Bienes Región Selva?</label>
                                        </div>
                                        <div class="SnSwitch">
                                            <input class="SnSwitch-input" id="jungle" type="checkbox">
                                            <label class="SnSwitch-label" for="jungle">¿Servicios Región Selva?</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="InvoiceFooter-total">
                                    <strong>Resumen:</strong>
                                    <table class="Invoice-totals">
                                        <tbody>
                                            <tr id="invoiceTotalDiscountRow">
                                                <th>Descuento Total (-)</th>
                                                <td class="InvoiceTotal">
                                                    <span class="InvoiceTotal-symbol jsCurrencySymbol">S/.</span>
                                                    <span class="InvoiceTotal-amount" id="invoiceTotalDiscountText">0.00</span>
                                                    <input type="hidden" name="invoiceTotalDiscount" id="invoiceTotalDiscount">
                                                </td>
                                            </tr>
                                            <tr id="invoiceTotalPrepaymentRow" class="SnHide">
                                                <th>Anticipo (-)</th>
                                                <td class="InvoiceTotal">
                                                    <span class="InvoiceTotal-symbol jsCurrencySymbol">S/.</span>
                                                    <span class="InvoiceTotal-amount" id="invoiceTotalPrepaymentText">0.00</span>
                                                    <input type="hidden" name="invoiceTotalPrepayment" id="invoiceTotalPrepayment">
                                                </td>
                                            </tr>
                                            <tr id="invoiceTotalExoneratedRow" class="SnHide">
                                                <th>Exonerada</th>
                                                <td class="InvoiceTotal">
                                                    <span class="InvoiceTotal-symbol jsCurrencySymbol">S/.</span>
                                                    <span class="InvoiceTotal-amount" id="invoiceTotalExoneratedText">0.00</span>
                                                    <input type="hidden" name="invoiceTotalExonerated" id="invoiceTotalExonerated">
                                                </td>
                                            </tr>
                                            <tr id="invoiceTotalUnaffectedRow" class="SnHide">
                                                <th>Inafecta</th>
                                                <td class="InvoiceTotal">
                                                    <span class="InvoiceTotal-symbol jsCurrencySymbol">S/.</span>
                                                    <span class="InvoiceTotal-amount" id="invoiceTotalUnaffectedText">0.00</span>
                                                    <input type="hidden" name="invoiceTotalUnaffected" id="invoiceTotalUnaffected">
                                                </td>
                                            </tr>
                                            <tr id="invoiceTotalExportRow" class="SnHide">
                                                <th>Exportación</th>
                                                <td class="InvoiceTotal">
                                                    <span class="InvoiceTotal-symbol jsCurrencySymbol">S/.</span>
                                                    <span class="InvoiceTotal-amount" id="invoiceTotalExportText">0.00</span>
                                                    <input type="hidden" name="invoiceTotalExport" id="invoiceTotalExport">
                                                </td>
                                            </tr>
                                            <tr id="invoiceTotalTaxedRow">
                                                <th>Gravada</th>
                                                <td class="InvoiceTotal">
                                                    <span class="InvoiceTotal-symbol jsCurrencySymbol">S/.</span>
                                                    <span class="InvoiceTotal-amount" id="invoiceTotalTaxedText">0.00</span>
                                                    <input type="hidden" name="invoiceTotalTaxed" id="invoiceTotalTaxed">
                                                </td>
                                            </tr>
                                            <tr id="invoiceTotalIscRow" class="SnHide">
                                                <th>ISC</th>
                                                <td class="InvoiceTotal">
                                                    <span class="InvoiceTotal-symbol jsCurrencySymbol">S/.</span>
                                                    <span class="InvoiceTotal-amount" id="invoiceTotalIscText">0.00</span>
                                                    <input type="hidden" name="invoiceTotalIsc" id="invoiceTotalIsc">
                                                </td>
                                            </tr>
                                            <tr id="invoiceTotalIgvRow">
                                                <th>IGV</th>
                                                <td class="InvoiceTotal">
                                                    <span class="InvoiceTotal-symbol jsCurrencySymbol">S/.</span>
                                                    <span class="InvoiceTotal-amount" id="invoiceTotalIgvText">0.00</span>
                                                    <input type="hidden" name="invoiceTotalIgv" id="invoiceTotalIgv">
                                                </td>
                                            </tr>
                                            <tr id="invoiceTotalFreeRow" class="SnHide">
                                                <th>Gratuita</th>
                                                <td class="InvoiceTotal">
                                                    <span class="InvoiceTotal-symbol jsCurrencySymbol">S/.</span>
                                                    <span class="InvoiceTotal-amount" id="invoiceTotalFreeText">0.00</span>
                                                    <input type="hidden" name="invoiceTotalFree" id="invoiceTotalFree">
                                                </td>
                                            </tr>
                                            <tr id="invoiceTotalPlasticBagTaxRow" class="SnHide">
                                                <th>ICBPER</th>
                                                <td class="InvoiceTotal">
                                                    <span class="InvoiceTotal-symbol jsCurrencySymbol">S/.</span>
                                                    <span class="InvoiceTotal-amount" id="invoiceTotalPlasticBagTaxText">0.00</span>
                                                    <input type="hidden" name="invoiceTotalPlasticBagTax" id="invoiceTotalPlasticBagTax">
                                                </td>
                                            </tr>
                                            <tr id="invoiceTotalRow">
                                                <th>Total</th>
                                                <td class="InvoiceTotal">
                                                    <span class="InvoiceTotal-symbol jsCurrencySymbol">S/.</span>
                                                    <span class="InvoiceTotal-amount" id="invoiceTotalText">0.00</span>
                                                    <input type="hidden" name="invoiceTotal" id="invoiceTotal">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="Invoice-send">
                            <button class="SnBtn primary lg block" type="submit">EMITIR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?= URL_PATH  ?>/assets/script/company/invoice.js"></script>
<?php require_once __DIR__ . '/layout/footer.php' ?>