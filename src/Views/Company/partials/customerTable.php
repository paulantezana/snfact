<div class="SnTable-wrapper">
    <table class="SnTable">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Tipo de Documento</th>
                <th>N° Documento</th>
                <th>Razón Social / Nombre Completo</th>
                <th>Estado</th>
                <th style="width: 100px"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customer['data'] as $row) : ?>
                <tr>
                    <td><?= $row['created_at'] ?></td>
                    <td><?= $row['identity_document_description'] ?></td>
                    <td><?= $row['document_number'] ?></td>
                    <td><?= $row['social_reason'] ?></td>
                    <td>
                        <div class="SnSwitch" style="height: 18px">
                            <input class="SnSwitch-input" type="checkbox" id="customerState<?= $row['customer_id']?>" type="checkbox" <?php echo $row['state'] ? 'checked' : '' ?> disabled>
                            <label class="SnSwitch-label" for="customerState<?= $row['customer_id']?>"></label>
                        </div>
                    </td>
                    <td>
                        <div class="SnTable-action">
                            <div class="SnBtn jsCustomerOption" data-tooltip="Editar" onclick="CustomerShowModalUpdate(<?= $row['customer_id'] ?>)">
                                <i class="icon-pencil"></i>
                            </div>
                            <div class="SnBtn jsCustomerOption" data-tooltip="Eliminar" onclick="CustomerDelete(<?= $row['customer_id'] ?>,'<?= $row['social_reason'] ?>')">
                                <i class="icon-trash"></i>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php
$currentPage = $customer['current'];
$totalPage = $customer['pages'];
$limitPage = $customer['limit'];
$additionalQuery = '';
$linksQuantity = 3;

if ($totalPage > 1) {
    $lastPage       = $totalPage;
    $startPage      = (($currentPage - $linksQuantity) > 0) ? $currentPage - $linksQuantity : 1;
    $endPage        = (($currentPage + $linksQuantity) < $lastPage) ? $currentPage + $linksQuantity : $lastPage;

    $htmlPaginate       = '<nav aria-label="..."><ul class="SnPagination">';

    $class      = ($currentPage == 1) ? "disabled" : "";
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="CustomerList(\''.($currentPage - 1). '\',\''.$limitPage.'\')" class="SnPagination-link">Anterior</a></li>';

    if ($startPage > 1) {
        $htmlPaginate   .= '<li class="SnPagination-item"><a href="#" onclick="CustomerList(\'1\',\''.$limitPage.'\')" class="SnPagination-link">1</a></li>';
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
    }

    for ($i = $startPage; $i <= $endPage; $i++) {
        $class  = ($currentPage == $i) ? "active" : "";
        $htmlPaginate   .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="CustomerList(\''.$i. '\',\''.$limitPage.'\')" class="SnPagination-link">' . $i . '</a></li>';
    }

    if ($endPage < $lastPage) {
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
        $htmlPaginate   .= '<li><a href="#" onclick="CustomerList(\''.$lastPage. '\',\''.$limitPage.'\')" class="SnPagination-link">' . $lastPage . '</a></li>';
    }

    $class      = ($currentPage == $lastPage || $totalPage == 0) ? "disabled" : "";
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="CustomerList(\''.($currentPage + 1). '\',\''.$limitPage.'\')" class="SnPagination-link">Siguiente</a></li>';

    $htmlPaginate       .= '</ul></nav>';

    echo  $htmlPaginate;
}
?>