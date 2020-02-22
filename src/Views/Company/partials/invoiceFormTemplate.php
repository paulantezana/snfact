<form action="" id="invoiceForm" onsubmit="invoiceSubmit()">
    <div class="Invoice">
        <div class="Invoice-top">
            <div class="SnSwitch">
                <input class="SnSwitch-control " id="includeIgv" type="checkbox">
                <label class="SnSwitch-label" for="includeIgv">Incluye IGV</label>
            </div>
            <div class="SnSwitch">
                <input class="SnSwitch-control " id="advancedOptions" type="checkbox" data-collapsetrigger="advancedOpt">
                <label class="SnSwitch-label" for="advancedOptions">Opciones avanzadas</label>
            </div>
        </div>
        <div class="Invoice-options">
            <div class="SnCollapse" data-collapse="advancedOpt">
                <div class="SnGrid m-grid-2 l-grid-3 lg-grid-4 SnMb-32">
                    <div class="SnForm-item required">
                        <label class="SnForm-label" for="invoiceDocumentCode">Documento</label>
                        <select class="SnForm-control" name="invoice[documentCode]" id="invoiceDocumentCode">
                            <?php foreach ($catDocumentTypeCode as $row) : ?>
                                <option value="<?= $row['code'] ?>" <?php echo ($invoiceDocumentCode ?? '') === $row['code'] ? 'selected' : '' ?> ><?= $row['description'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="SnForm-item required">
                        <label class="SnForm-label" for="invoiceOperationCode">Tipo de operacion</label>
                        <select class="SnForm-control" name="invoice[operationCode]" id="invoiceOperationCode">
                            <?php foreach ($catOperationTypeCode as $row) : ?>
                                <option value="<?= $row['code'] ?>"><?= $row['description'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="SnForm-item required">
                        <label class="SnForm-label" for="invoiceCurrencyCode">Moneda</label>
                        <select class="SnForm-control" name="invoice[currencyCode]" id="invoiceCurrencyCode">
                            <?php foreach ($catCurrencyTypeCode as $row) : ?>
                                <option value="<?= $row['code'] ?>" data-symbol="<?= $row['symbol'] ?>" <?= $row['code'] == 'PEN' ? 'selected' : '' ?>><?= $row['description'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="SnForm-item required">
                        <label class="SnForm-label" for="invoiceSerie">Serie:</label>
                        <select name="invoice[serie]" id="invoiceSerie" class="SnForm-control">
                            <?php foreach ($invoiceSerieNumber as $row) : ?>
                                <option value="<?= $row['serie'] ?>"><?= $row['serie'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="SnForm-item required">
                        <label class="SnForm-label" for="invoiceNumber">Número</label>
                        <div class="SnControl-wrapper">
                            <i class="icon-file-text2 SnControl-prefix"></i>
                            <input class="SnForm-control SnControl" type="text" name="invoice[number]" id="invoiceNumber" placeholder="#">
                        </div>
                    </div>
                    <div class="SnForm-item required">
                        <label class="SnForm-label" for="invoicePdfFormat">PDF Formato</label>
                        <select name="invoice[pdfFormat]" class="SnForm-control" id="invoicePdfFormat">
                            <option value="A4">TAMAÑO A4</option>
                            <option value="A5">TAMAÑO A5 (MITAD DE A4)</option>
                            <option value="TICKET">TAMAÑO TICKET</option>
                        </select>
                    </div>
                    <div class="SnForm-item required">
                        <label class="SnForm-label" for="invoiceDateOfIssue">Fecha.Doc:</label>
                        <input class="SnForm-control" type="date" name="invoice[dateOfIssue]" id="invoiceDateOfIssue">
                    </div>
                    <div class="SnForm-item required">
                        <label class="SnForm-label" for="invoiceDateOfDue">Fecha.Venc.:</label>
                        <input class="SnForm-control" type="date" name="invoice[dateOfDue]" id="invoiceDateOfDue">
                    </div>
                    <div class="SnForm-item">
                        <label class="SnForm-label" for="invoiceChangeType">Tipo Cambio (SUNAT):</label>
                        <div class="SnControl-wrapper">
                            <i class="icon-file-text2 SnControl-prefix"></i>
                            <input class="SnForm-control SnControl" type="text" name="invoice[changeType]" id="invoiceChangeType">
                        </div>
                    </div>
                    <div class="SnForm-item">
                        <label class="SnForm-label" for="invoiceVehiclePlate">N° Placa Vehículo:</label>
                        <div class="SnControl-wrapper">
                            <i class="icon-file-text2 SnControl-prefix"></i>
                            <input class="SnForm-control SnControl" type="text" name="invoice[vehiclePlate]" id="invoiceVehiclePlate" placeholder="Número de placa">
                        </div>
                    </div>
                    <div class="SnForm-item">
                        <label class="SnForm-label" for="invoicePurchaseOrder">N° de Orden:</label>
                        <div class="SnControl-wrapper">
                            <i class="icon-file-text2 SnControl-prefix"></i>
                            <input class="SnForm-control SnControl" type="text" name="invoice[purchaseOrder]" id="invoicePurchaseOrder" placeholder="Número de orden">
                        </div>
                    </div>
                    <div class="SnForm-item">
                        <label class="SnForm-label" for="invoiceTerm">Condiciiones de pago:</label>
                        <div class="SnControl-wrapper">
                            <i class="icon-file-text2 SnControl-prefix"></i>
                            <input class="SnForm-control SnControl" type="text" name="invoice[term]" id="invoiceTerm" placeholder="Condiciiones de pago">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="Invoice-header">
            <div class="SnGrid m-grid-2 l-grid-3 SnMb-32">
                <div class="SnForm-item required">
                    <label class="SnForm-label" for="invoiceCustomerDocumentNumber">N° de R.U.C.:</label>
                    <div class="SnControl-group">
                        <div class="SnControl-wrapper">
                            <i class="icon-user SnControl-prefix"></i>
                            <input class="SnForm-control SnControl" type="text" name="invoice[customer][documentNumber]" id="invoiceCustomerDocumentNumber" placeholder="Número de documento Aquí!">
                        </div>
                        <div class="SnBtn primary"><i class="icon-search4"></i></div>
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
                        <i class="icon-home2 SnControl-prefix"></i>
                        <input class="SnForm-control SnControl" type="text" name="invoice[customer][address]" id="invoiceCustomerAddress" placeholder="Escribe aquí la dirección completa">
                    </div>
                </div>
                <div class="SnForm-item">
                    <label class="SnForm-label" for="invoiceCustomerLocation">Ubigeo:</label>
                    <div class="SnControl-wrapper">
                        <i class="icon-sphere SnControl-prefix"></i>
                        <input class="SnForm-control SnControl" type="text" name="invoice[customer][location]" id="invoiceCustomerLocation" placeholder="Selecciona Tu Código de Ubigeo">
                    </div>
                </div>
                <div class="SnForm-item SnSwitch l-cols-2 required">
                    <input class="SnSwitch-control" type="checkbox" name="invoice[customer][sendEmail]" id="invoiceCustomerSendEmail" data-collapsetrigger="sendEmail">
                    <label class="SnSwitch-label" for="invoiceCustomerSendEmail" >¿Deseas Enviar el Comprobante Electrónico al Email del Cliente?:</label>
                </div>
                <div class="SnForm-item required SnCollapse" data-collapse="sendEmail">
                    <label class="SnForm-label" for="invoiceCustomerEmail">Email:</label>
                    <div class="SnControl-wrapper">
                        <i class="icon-envelop2 SnControl-prefix"></i>
                        <input class="SnForm-control SnControl" type="text" name="invoice[customer][email]" id="invoiceCustomerEmail" placeholder="Escribe aquí el email del cliente">
                    </div>
                </div>
            </div>
        </div>
        <div class="Invoice-body">
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
                                <th style="width: 95px"></th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <td colspan="7">
                                    <div class="SnBtn block"
                                         onclick="addItem()"
                                         id="addInvoiceItem"
                                         data-itemtemplate="<?php echo htmlspecialchars(($invoiceItemTemplate ?? ''),ENT_QUOTES) ?>">
                                        <i class="icon-plus2"></i> Agregar item
                                    </div>
                                </td>
                            </tr>
                            </tfoot>
                            <tbody id="invoiceItemTableBody"></tbody>
                        </table>
                    </div>
                </div>
                <div class="SnTab-content">
                    en proceso...
                </div>
                <div class="SnTab-content">
                    en proceso...
                </div>
                <div class="SnTab-content">
                    en proceso...
                </div>
                <div class="SnTab-content">
                    en proceso...
                </div>
                <div class="SnTab-header">
                    <div class="SnTab-title is-active">
                        <i class="icon-list2 SnMr-2"></i>
                        Productos
                    </div>
                    <div class="SnTab-title">
                        <i class="icon-safe SnMr-2"></i>
                        Régimen
                    </div>
                    <div class="SnTab-title">
                        <i class="icon-nbsp SnMr-2"></i>
                        Factura guia
                    </div>
                    <div class="SnTab-title">
                        <i class="icon-file-xml2 SnMr-2"></i>
                        Otros doc.
                    </div>
                    <div class="SnTab-title">
                        <i class="icon-package SnMr-2"></i>
                        Anticipo
                    </div>
                </div>
            </div>
        </div>
        <div class="Invoice-footer">
            <div class="InvoiceFooter">
                <div class="InvoiceFooter-item">
                    <div class="SnForm-item">
                        <label class="SnForm-label" for="invoiceGlobalDiscountPercentage">Descuento Total (en porcentaje %):</label>
                        <div class="SnControl-wrapper">
                            <span class="SnControl-prefix">%</span>
                            <input class="SnForm-control SnControl" type="number" name="invoice[globalDiscountPercentage]" id="invoiceGlobalDiscountPercentage" placeholder="0.00">
                        </div>
                    </div>
                    <div class="SnForm-item">
                        <label class="SnForm-label" for="invoiceTotalOtherCharger">Otros Cargos:</label>
                        <div class="SnControl-wrapper">
                            <span class="jsCurrencySymbol SnControl-prefix"></span>
                            <input class="SnForm-control SnControl" type="number" name="invoice[totalOtherCharger]" id="invoiceTotalOtherCharger" placeholder="0.00">
                        </div>
                    </div>
                    <div class="SnForm-item">
                        <label class="SnForm-label" for="invoiceObservation">Observación:</label>
                        <textarea class="SnForm-control" name="invoice[observation]" id="invoiceObservation" cols="30" rows="5" placeholder="Escribe aquí una observación"></textarea>
                    </div>
                    <div class="SnGrid s-grid-2">
                        <div class="SnSwitch">
                            <input class="SnSwitch-control" name="invoice[jungleProduct]" id="service" type="checkbox">
                            <label class="SnSwitch-label" for="service">¿Bienes Región Selva?</label>
                        </div>
                        <div class="SnSwitch">
                            <input class="SnSwitch-control" name="invoice[jungleService]" id="jungle" type="checkbox">
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
                                <span class="InvoiceTotal-symbol jsCurrencySymbol"></span>
                                <span class="InvoiceTotal-amount" id="invoiceTotalDiscountText">0.00</span>
                                <input type="hidden" name="invoice[totalDiscount]" id="invoiceTotalDiscount">
                            </td>
                        </tr>
                        <tr id="invoiceTotalPrepaymentRow" class="SnHide">
                            <th>Anticipo (-)</th>
                            <td class="InvoiceTotal">
                                <span class="InvoiceTotal-symbol jsCurrencySymbol"></span>
                                <span class="InvoiceTotal-amount" id="invoiceTotalPrepaymentText">0.00</span>
                                <input type="hidden" name="invoice[totalPrepayment]" id="invoiceTotalPrepayment">
                            </td>
                        </tr>
                        <tr id="invoiceTotalExoneratedRow" class="SnHide">
                            <th>Exonerada</th>
                            <td class="InvoiceTotal">
                                <span class="InvoiceTotal-symbol jsCurrencySymbol"></span>
                                <span class="InvoiceTotal-amount" id="invoiceTotalExoneratedText">0.00</span>
                                <input type="hidden" name="invoice[totalExonerated]" id="invoiceTotalExonerated" class="jsInvoiceTotals">
                            </td>
                        </tr>
                        <tr id="invoiceTotalUnaffectedRow" class="SnHide">
                            <th>Inafecta</th>
                            <td class="InvoiceTotal">
                                <span class="InvoiceTotal-symbol jsCurrencySymbol"></span>
                                <span class="InvoiceTotal-amount" id="invoiceTotalUnaffectedText">0.00</span>
                                <input type="hidden" name="invoice[totalUnaffected]" id="invoiceTotalUnaffected" class="jsInvoiceTotals">
                            </td>
                        </tr>
                        <tr id="invoiceTotalExportRow" class="SnHide">
                            <th>Exportación</th>
                            <td class="InvoiceTotal">
                                <span class="InvoiceTotal-symbol jsCurrencySymbol"></span>
                                <span class="InvoiceTotal-amount" id="invoiceTotalExportText">0.00</span>
                                <input type="hidden" name="invoice[totalExport]" id="invoiceTotalExport" class="jsInvoiceTotals">
                            </td>
                        </tr>
                        <tr id="invoiceTotalTaxedRow">
                            <th>Gravada</th>
                            <td class="InvoiceTotal">
                                <span class="InvoiceTotal-symbol jsCurrencySymbol"></span>
                                <span class="InvoiceTotal-amount" id="invoiceTotalTaxedText">0.00</span>
                                <input type="hidden" name="invoice[totalTaxed]" id="invoiceTotalTaxed" class="jsInvoiceTotals">
                            </td>
                        </tr>
                        <tr id="invoiceTotalIscRow" class="SnHide">
                            <th>ISC</th>
                            <td class="InvoiceTotal">
                                <span class="InvoiceTotal-symbol jsCurrencySymbol"></span>
                                <span class="InvoiceTotal-amount" id="invoiceTotalIscText">0.00</span>
                                <input type="hidden" name="invoice[totalBaseIsc]" id="invoiceTotalBaseIsc">
                                <input type="hidden" name="invoice[totalIsc]" id="invoiceTotalIsc">
                            </td>
                        </tr>
                        <tr id="invoiceTotalIgvRow">
                            <th>IGV</th>
                            <td class="InvoiceTotal">
                                <span class="InvoiceTotal-symbol jsCurrencySymbol"></span>
                                <span class="InvoiceTotal-amount" id="invoiceTotalIgvText">0.00</span>
                                <input type="hidden" name="invoice[totalBaseIgv]" id="invoiceTotalBaseIgv">
                                <input type="hidden" name="invoice[totalIgv]" id="invoiceTotalIgv" class="jsInvoiceTotals">
                            </td>
                        </tr>
                        <tr id="invoiceTotalFreeRow" class="SnHide">
                            <th>Gratuita</th>
                            <td class="InvoiceTotal">
                                <span class="InvoiceTotal-symbol jsCurrencySymbol"></span>
                                <span class="InvoiceTotal-amount" id="invoiceTotalFreeText">0.00</span>
                                <input type="hidden" name="invoice[totalFree]" id="invoiceTotalFree">
                            </td>
                        </tr>
                        <tr id="invoiceTotalPlasticBagTaxRow" class="SnHide">
                            <th>ICBPER</th>
                            <td class="InvoiceTotal">
                                <span class="InvoiceTotal-symbol jsCurrencySymbol"></span>
                                <span class="InvoiceTotal-amount" id="invoiceTotalPlasticBagTaxText">0.00</span>
                                <input type="hidden" name="invoice[totalPlasticBagTax]" id="invoiceTotalPlasticBagTax">
                            </td>
                        </tr>
                        <tr id="invoiceTotalRow">
                            <th>Total</th>
                            <td class="InvoiceTotal">
                                <span class="InvoiceTotal-symbol jsCurrencySymbol"></span>
                                <span class="InvoiceTotal-amount" id="invoiceTotalText">0.00</span>
                                <input type="hidden" name="invoice[total]" id="invoiceTotal">
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