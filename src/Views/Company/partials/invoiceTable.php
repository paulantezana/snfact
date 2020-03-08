<div class="SnTable-wrapper">
    <table class="SnTable">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Comprobante</th>
                <th>Cliente</th>
                <th>Total</th>
                <th style="width: 54px">Email</th>
                <th style="width: 54px">PDF</th>
                <th style="width: 54px">XML</th>
                <th style="width: 54px">CDR</th>
                <th style="width: 60px">Sunat</th>
                <th style="width: 60px"></th>
            </tr>
        </thead>
        <tfoot></tfoot>
        <tbody>
            <?php if (count($parameter['invoice']['data']) >= 1): foreach ($parameter['invoice']['data'] as $row) : ?>
                <tr>
                    <td><?php echo $row['date_of_issue'] ?> / <?php echo $row['time_of_issue'] ?></td>
                    <td><?php echo $row['document_type_code_description'] . ': ' . $row['serie'] . ' - ' . $row['number'] ?></td>
                    <td>
                        <div><?php echo $row['customer_document_number'] ?></div>
                        <div><?php echo $row['customer_social_reason'] ?></div>
                    </td>
                    <td><?php echo $row['total'] ?></td>
                    <td>
                        <?php if ($row['customer_sent_to_client']): ?>
                            <i class="icon-checkmark" title="Enviado al cliente"></i>
                        <?php else: ?>
                            <i class="icon-blocked" title="No se envio al cliente"></i>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['pdf_url'] != ''): ?>
                            <div class="SnBtn icon error" onclick="DocumentPrinter.showModal('<?= $row['pdf_url'] ?? '' ?>', false)" title="PDF"><i class="icon-file-pdf text-danger"></i></div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['xml_url'] != ''): ?>
                            <a
                                    href="<?php echo URL_PATH . $row['xml_url'] ?>"
                                    download="<?php $fileName = explode('/', $row['xml_url'] ?? ''); echo  'XML-'. $fileName[count($fileName) - 1]?>"
                                    class="SnBtn icon success"
                                    title="XML"
                            >
                                <i class="icon-file-xml"></i>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['cdr_url'] != ''): ?>
                            <a
                                    href="<?php echo URL_PATH . $row['cdr_url'] ?? '' ?>"
                                    download="<?php $fileName = explode('/', $row['cdr_url'] ?? ''); echo  'CDR-'. $fileName[count($fileName) - 1]?>"
                                    class="SnBtn icon primary"
                                    title="CDR"
                            >
                                <i class="icon-file-xml"></i>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="SnDropdown SnBtn icon">
                            <div class="SnDropdown-toggle"><i class="icon-blocked"></i></div>
                            <ul class="SnDropdown-list">

                            </ul>
                        </div>
                    </td>
                    <td>
                        <!-- <div class="SnBtn-group"> -->
                            <div class="SnDropdown SnBtn icon">
                                <div class="SnDropdown-toggle"><i class="icon-menu9"></i></div>
                                <ul class="SnDropdown-list">
                                    <li class="SnDropdown-item" onclick="invoiceResend('<?= $row['invoice_id']?>')"><i class="icon-spinner11 text-success  SnMr-2"></i> Consultar o recuperar constancia</li>
                                    <li class="SnDropdown-item" onclick="InvoiceSendEmailOpenModal('<?= $row['invoice_id']?>','<?= $row['customer_email'] ?>')"><i class="icon-envelop SnMr-2"></i>  Enviar a un email personalizado</li>
                                    <li class="SnDropdown-item"><a href="<?= URL_PATH . '/invoice/NewCreditNote?invoiceId=' . $row['invoice_id'] ?>"> <i class="icon-file-text SnMr-2"></i> Generar NOTA DE CREDITO</a></li>
                                    <li class="SnDropdown-item"><a href="<?= URL_PATH . '/invoice/NewDebitNote?invoiceId=' . $row['invoice_id'] ?>"> <i class="icon-file-text SnMr-2"></i> Generar NOTA DE DEBITO</a></li>
                                    <li class="SnDropdown-item"><a href="<?= URL_PATH . '/invoice/NewGuide?invoiceId=' . $row['invoice_id']  ?>"> <i class="icon-file-text SnMr-2"></i> Generar GUIA DE REMISIÓN</a></li>
                                    <li class="SnDropdown-item"><a href="<?= URL_PATH . '/invoice/NewInvoiceVoided?invoiceId=' . $row['invoice_id'] ?>"><i class="icon-cancel-circle2 text-danger SnMr-2"></i> ANULAR o COMUNICAR DE BAJA</a></li>
                                    <li class="SnDropdown-item"><a href="#" target="_blank"> <img src="<?= URL_PATH . '/assets/images/sunatLogo.png'?>" style="height: 18px" class="SnMr-2"> Verificar en la SUNAT la validéz del CPE</a></li>
                                    <li class="SnDropdown-item"><a href="#" target="_blank"> <img src="<?= URL_PATH . '/assets/images/sunatLogo.png'?>" style="height: 18px" class="SnMr-2"> Verificar XML en la SUNAT</a></li>
                                </ul>
                            </div>
                        <!-- </div> -->
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