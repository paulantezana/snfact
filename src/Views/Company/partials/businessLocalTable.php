<div class="SnTable-wrapper">
    <table class="SnTable">
        <thead>
            <tr>
                <th>CÓDIGO</th>
                <th>Nombre de Sucursal</th>
                <th>Ubigeo</th>
                <th>Dirección</th>
                <th>pdf_invoice_size</th>
                <th style="width: 100px"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($businessLocal['data'] as $row) : ?>
                <tr>
                    <td><?= $row['sunat_code'] ?></td>
                    <td><?= $row['short_name'] ?></td>
                    <td><?= $row['location_code'] ?></td>
                    <td><?= $row['address'] ?></td>
                    <td><?= $row['pdf_invoice_size'] ?></td>
                    <td>
                        <div class="SnTable-action">
                            <div class="SnBtn jsProductOption" data-tooltip="Editar" onclick="BusinessLocalShowModalUpdate(<?= $row['business_local_id'] ?>)">
                                <i class="icon-pencil"></i>
                            </div>
                            <div class="SnBtn jsProductOption" data-tooltip="Eliminar" onclick="BusinessLocalDelete(<?= $row['business_local_id'] ?>,'<?= $row['short_name'] ?>')">
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
$currentPage = $businessLocal['current'];
$totalPage = $businessLocal['pages'];
$limitPage = $businessLocal['limit'];
$additionalQuery = '';
$linksQuantity = 3;

if ($totalPage > 1) {
    $lastPage       = $totalPage;
    $startPage      = (($currentPage - $linksQuantity) > 0) ? $currentPage - $linksQuantity : 1;
    $endPage        = (($currentPage + $linksQuantity) < $lastPage) ? $currentPage + $linksQuantity : $lastPage;

    $htmlPaginate       = '<nav aria-label="..."><ul class="SnPagination">';

    $class      = ($currentPage == 1) ? "disabled" : "";
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="ProductForm.list(\''.($currentPage - 1). '\',\''.$limitPage.'\')" class="SnPagination-link">Anterior</a></li>';

    if ($startPage > 1) {
        $htmlPaginate   .= '<li class="SnPagination-item"><a href="#" onclick="ProductForm.list(\'1\',\''.$limitPage.'\')" class="SnPagination-link">1</a></li>';
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
    }

    for ($i = $startPage; $i <= $endPage; $i++) {
        $class  = ($currentPage == $i) ? "active" : "";
        $htmlPaginate   .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="ProductForm.list(\''.$i. '\',\''.$limitPage.'\')" class="SnPagination-link">' . $i . '</a></li>';
    }

    if ($endPage < $lastPage) {
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
        $htmlPaginate   .= '<li><a href="#" onclick="ProductForm.list(\''.$lastPage. '\',\''.$limitPage.'\')" class="SnPagination-link">' . $lastPage . '</a></li>';
    }

    $class      = ($currentPage == $lastPage || $totalPage == 0) ? "disabled" : "";
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="ProductForm.list(\''.($currentPage + 1). '\',\''.$limitPage.'\')" class="SnPagination-link">Siguiente</a></li>';

    $htmlPaginate       .= '</ul></nav>';

    echo  $htmlPaginate;
}
?>