<div class="SnTable-wrapper">
    <table class="SnTable">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Comprobante</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>PDF</th>
                <th>XML</th>
                <th>CDR</th>
                <th>Sunat</th>
                <th></th>
            </tr>
        </thead>
        <tfoot></tfoot>
        <tbody>
            <?php if (count($invoice['data']) >= 1): foreach ($invoice['data'] as $row) : ?>
                <tr>
                    <td><?php echo $row['date_of_issue'] ?></td>
                    <td><?php echo $row['document_type_code_description'] . ': ' . $row['serie'] . $row['number'] ?></td>
                    <td>
                        <div><?php echo $row['customer_document_number'] ?></div>
                        <div><?php echo $row['customer_social_reason'] ?></div>
                    </td>
                    <td><?php echo $row['total'] ?></td>
                    <td>
                        <?php if ($row['customer_sent_to_client']): ?>
                            <i class="icon-checkmark mr-2" title="Enviado al cliente"></i>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['pdf_url'] != ''): ?>
                            <div class="SnBtn" onclick="DocumentPrinter.showModal('<?= $row['pdf_url'] ?? '' ?>', false)" title="PDF"><i class="icon-file-pdf text-danger"></i></div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['xml_url'] != ''): ?>
                            <a
                                    href="..<?= $row['xml_url'] ?? '' ?>"
                                    download="<?php $fileName = explode('/', $row['xml_url'] ?? ''); echo  'XML-'. $fileName[count($fileName) - 1]?>"
                                    class="SnBtn"
                                    title="XML"
                            >
                                <i class="icon-file-xml"></i>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td></td>
                    <td>
                        <div class="SnBtn primary" onclick="InvoiceSendEmailOpenModal('<?= $row['invoice_id']?>','<?= $row['customer_email'] ?>')">Open Modal</div>
                        <div class="SnDropdown">
                            <div class="SnDropdown-toggle"></div>
                            <ul class="SnDropdown-list">
                                <li class="SnDropdown-item"><a href="<?= URL_PATH . '/Invoice/ResendInvoice?InvoiceId=' . $row['invoice_id'] ?>"><i class="icon-spinner11 text-success  mr-2"></i> Consultar o recuperar constancia</a></li>
                                <li class="SnDropdown-item"><a href="#" data-toggle="modal" data-target="#invoiceSendEmailModal"><i class="icon-envelop mr-2"></i>  Enviar a un email personalizado</a></li>
                                <li class="SnDropdown-item"><a href="<?= URL_PATH . '/InvoiceNote/NewCreditNote?InvoiceId=' . $row['invoice_id'] ?>"> <i class="icon-file-text mr-2"></i> Generar NOTA DE CREDITO</a></li>
                                <li class="SnDropdown-item"><a href="<?= URL_PATH . '/InvoiceNote/NewDebitNote?InvoiceId=' . $row['invoice_id'] ?>"> <i class="icon-file-text mr-2"></i> Generar NOTA DE DEBITO</a></li>
                                <li class="SnDropdown-item"><a href="<?= URL_PATH . '/ReferralGuide/NewGuide?InvoiceId=' . $row['invoice_id']  ?>"> <i class="icon-file-text mr-2"></i> Generar GUIA DE REMISIÓN</a></li>
                                <li class="SnDropdown-item"><a href="<?= URL_PATH . '/InvoiceVoided/NewInvoiceVoided?InvoiceId=' . $row['invoice_id'] ?>"><i class="icon-cancel-circle2 text-danger mr-2"></i> ANULAR o COMUNICAR DE BAJA</a></li>
                                <li class="SnDropdown-item"><a href="#" target="_blank"> <img src="<?= URL_PATH . '/Asset/Images/sunatLogo.png'?>" height="16px" class="mr-2"> Verificar en la SUNAT la validéz del CPE</a></li>
                                <li class="SnDropdown-item"><a href="#" target="_blank"> <img src="<?= URL_PATH . '/Asset/Images/sunatLogo.png'?>" height="16px" class="mr-2"> Verificar XML en la SUNAT</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            <?php endforeach; else: ?>
                <tr>
                    <td colspan="9">
                        <div class="SnEmpty">
                            <img src="<?= URL_PATH . '/assets/images/empty.svg' ?>" alt="">
                            <div>No hay datos</div>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>