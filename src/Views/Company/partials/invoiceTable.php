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