<div class="SnTable-wrapper">
    <table class="SnTable">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Comprobante</th>
                <th>Cliente</th>
                <th>Total</th>
                <th style="width: 50px">Email</th>
                <th style="width: 50px">PDF</th>
                <th style="width: 50px">XML</th>
                <th style="width: 50px">CDR</th>
                <th style="width: 50px">Sunat</th>
                <th style="width: 50px"></th>
            </tr>
        </thead>
        <tfoot></tfoot>
        <tbody>
            <?php if (count($parameter['invoice']['data']) >= 1): foreach ($parameter['invoice']['data'] as $row) : ?>
                <tr>
                    <td><?php echo $row['date_of_issue'] ?> / <?php echo $row['time_of_issue'] ?></td>
                    <td>
                        <div><?php echo $row['document_type_code_description'] . ': ' . $row['serie'] . ' - ' . $row['number'] ?></div>
                        <?php if($row['document_code'] == '07' || $row['document_code'] == '08'): ?>
                            <div class="invoice InvoiceTable-refer">
                              <?php $colorDot = $row['document_code'] == '07' ? 'var(--snColor1)' : 'var(--snError)' ?>
                              <i class="fas fa-dot-circle SnMr-2" style="color: <?= $colorDot?>; font-size: 0.65em"></i>Modifica: <?php echo $row['update_serie'] . '-' . $row['update_number'] ?>
                            </div>
                        <?php endif;?>
                    </td>
                    <td>
                        <?php echo $row['customer_document_number'] ?><br>
                        <?php echo $row['customer_social_reason'] ?>
                    </td>
                    <td><?php echo $row['total'] ?></td>
                    <td style="text-align: center">
                        <?php if ($row['customer_sent_to_client']): ?>
                            <i class="fas fa-check" title="Enviado al cliente" style="color: var(--snSuccess)"></i>
                        <?php else: ?>
                            <i class="fas fa-ban" title="No se envio al cliente" style="color: var(--snColorTextAlt)"></i>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['pdf_url'] != ''): ?>
                            <div class="SnBtn icon error" onclick="DocumentPrinter.showModal('<?= $row['pdf_url'] ?? '' ?>', false)" title="PDF"><i class="fas fa-file-pdf"></i></div>
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
                                <i class="fas fa-file-code"></i>
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
                                <i class="fas fa-file-contract"></i>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="SnDropdown">
                            <div class="SnDropdown-toggle SnBtn icon">
                              <?php if($row['document_code'] == '03' || $row['update_document_code'] == '03'): ?>
                                <?php if($row['invoice_state_id'] == '1'): ?>
                                  <i class="fas fa-chevron-circle-right"></i>
                                <?php elseif ($row['invoice_state_id'] == '2'): ?>
                                  <i class="fas fa-sync-alt" style="color: var(--snColor1)"></i>
                                <?php elseif ($row['invoice_state_id'] == '3'): ?>
                                  <i class="fas fa-chevron-circle-right" style="color: var(--snColor1)"></i>
                                <?php elseif ($row['invoice_state_id'] == '4'): ?>
                                  <i class="fas fa-ban" style="color: var(--snError)"></i>
                                <?php endif; ?>
                              <?php elseif ($row['document_code'] == '01' || $row['update_document_code'] == '01'): ?>
                                <?php if($row['invoice_state_id'] == '1'): ?>
                                  <i class="fas fa-sync-alt"></i>
                                <?php elseif ($row['invoice_state_id'] == '2'): ?>
                                  <i class="fas fa-sync-alt" style="color: var(--snWarning)"></i>
                                <?php elseif ($row['invoice_state_id'] == '3'): ?>
                                  <i class="fas fa-check" style="color: var(--snSuccess)"></i>
                                <?php elseif ($row['invoice_state_id'] == '4'): ?>
                                  <i class="fas fa-ban" style="color: var(--snError)"></i>
                                <?php endif; ?>
                              <?php endif; ?>
                            </div>
                            <ul class="SnDropdown-list" style="min-width: 300px">
                              <?php if($row['document_code'] == '03' || $row['update_document_code'] == '03'): ?>
                                <li class="SnDropdown-item">Este documento se enviará a la SUNAT en un Resumen Diario al día siguiente.</li>
                                <li class="SnDropdown-item">
                                  <div>Aceptada por la SUNAT</div>
                                  <div></div>
                                </li>
                                <li class="SnDropdown-item">
                                  <div>Descripción</div>
                                  <div><?= $row['response_message'] ?></div>
                                </li>
                                <li class="SnDropdown-item">
                                  <div>Otros</div>
                                  <div><?= $row['other_message'] ?></div>
                                </li>
                              <?php elseif ($row['document_code'] == '01' || $row['update_document_code'] == '01'): ?>
                                <li class="SnDropdown-item">
                                  <div>Enviada a la SUNAT</div>
                                  <div></div>
                                </li>
                                <li class="SnDropdown-item">
                                  <div>Aceptada por la SUNAT</div>
                                  <div></div>
                                </li>
                                <li class="SnDropdown-item">
                                  <div>Código</div>
                                  <div><?= $row['response_code'] ?></div>
                                </li>
                                <li class="SnDropdown-item">
                                  <div>Descripción</div>
                                  <div><?= $row['response_message'] ?></div>
                                </li>
                                <li class="SnDropdown-item">
                                  <div>Otros</div>
                                  <div><?= $row['other_message'] ?></div>
                                </li>
                              <?php endif; ?>
                            </ul>
                        </div>
                    </td>
                    <td>
                        <div class="SnDropdown">
                            <div class="SnDropdown-toggle SnBtn icon"><i class="fas fa-bars"></i></div>
                            <ul class="SnDropdown-list" style="min-width: 300px">
                                <?php if(($row['invoice_state_id'] == '1' || $row['invoice_state_id'] == '2') && ($row['document_code'] == '01' || $row['update_document_code'] == '01')): ?>
                                  <li class="SnDropdown-item" onclick="invoiceResend('<?= $row['invoice_id']?>')">
                                    <i class="fas fa-redo SnMr-2"></i>Consultar o recuperar constancia
                                  </li>
                                <?php endif; ?>
                                <li class="SnDropdown-item" onclick="invoiceSendEmailOpenModal('<?= $row['invoice_id']?>','<?= $row['customer_email'] ?>')">
                                  <i class="far fa-envelope SnMr-2"></i>Enviar a un email personalizado
                                </li>
                                <?php if($row['document_code'] == '01' || $row['document_code'] == '03'):?>
                                    <li class="SnDropdown-item">
                                      <a href="<?= URL_PATH . '/invoice/newInvoice?documentCode=07&invoiceId=' . $row['invoice_id'] ?>"><i class="far fa-clipboard SnMr-2"></i>Crear nota de credito</a>
                                    </li>
                                    <li class="SnDropdown-item">
                                      <a href="<?= URL_PATH . '/invoice/newInvoice?documentCode=08&invoiceId=' . $row['invoice_id'] ?>"><i class="far fa-clipboard SnMr-2"></i>Crear nota de debito</a>
                                    </li>
                                <?php endif; ?>
                                <li class="SnDropdown-item">
                                  <a href="<?= URL_PATH . '/invoice/NewGuide?invoiceId=' . $row['invoice_id']  ?>"><i class="far fa-sticky-note SnMr-2"></i>Crear guia de remisión</a>
                                </li>
                                <?php if($row['invoice_state_id'] == '3'): ?>
                                  <li class="SnDropdown-item">
                                    <a href="<?= URL_PATH . '/invoice/NewInvoiceVoided?invoiceId=' . $row['invoice_id'] ?>"><i class="fas fa-ban SnMr-2"></i>Anular Comprobante</a>
                                  </li>
                                <?php endif; ?>
                                <li class="SnDropdown-item" onclick="invoiceValidateDocument('<?= $row['invoice_id']?>')">
                                  <i class="fas fa-check SnMr-2"></i>Verificar estado en SUNAT
                                </li>
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

<?php
$currentPage = $parameter['invoice']['current'];
$totalPage = $parameter['invoice']['pages'];
$limitPage = $parameter['invoice']['limit'];
$additionalQuery = '';
$linksQuantity = 3;

if ($totalPage > 1) {
  $lastPage       = $totalPage;
  $startPage      = (($currentPage - $linksQuantity) > 0) ? $currentPage - $linksQuantity : 1;
  $endPage        = (($currentPage + $linksQuantity) < $lastPage) ? $currentPage + $linksQuantity : $lastPage;

  $htmlPaginate       = '<nav aria-label="..."><ul class="SnPagination">';

  $class      = ($currentPage == 1) ? "disabled" : "";
  $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="invoiceList(\''.($currentPage - 1). '\',\''.$limitPage.'\')" class="SnPagination-link">Anterior</a></li>';

  if ($startPage > 1) {
    $htmlPaginate   .= '<li class="SnPagination-item"><a href="#" onclick="invoiceList(\'1\',\''.$limitPage.'\')" class="SnPagination-link">1</a></li>';
    $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
  }

  for ($i = $startPage; $i <= $endPage; $i++) {
    $class  = ($currentPage == $i) ? "active" : "";
    $htmlPaginate   .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="invoiceList(\''.$i. '\',\''.$limitPage.'\')" class="SnPagination-link">' . $i . '</a></li>';
  }

  if ($endPage < $lastPage) {
    $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
    $htmlPaginate   .= '<li><a href="#" onclick="invoiceList(\''.$lastPage. '\',\''.$limitPage.'\')" class="SnPagination-link">' . $lastPage . '</a></li>';
  }

  $class      = ($currentPage == $lastPage || $totalPage == 0) ? "disabled" : "";
  $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="invoiceList(\''.($currentPage + 1). '\',\''.$limitPage.'\')" class="SnPagination-link">Siguiente</a></li>';

  $htmlPaginate       .= '</ul></nav>';

  echo  $htmlPaginate;
}
?>
