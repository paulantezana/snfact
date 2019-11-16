<div class="SnTable-wrapper">
    <table class="SnTable">
        <thead>
            <tr>
                <th>Nombre completo</th>
                <th>Documento</th>
                <th>Telefono</th>
                <th>Estado</th>
                <th style="width: 100px"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customer['data'] as $row) : ?>
                <tr>
                    <td><?= $row['full_name'] ?></td>
                    <td><?= $row['document'] ?></td>
                    <td><?= $row['phone'] ?></td>
                    <td><?= $row['state'] ?></td>
                    <td>
                        <div class="SnTable-action">
                            <div class="SnBtn jsCustomerOption" data-tooltip="Editar" onclick="CustomerForm.executeUpdateNormal(<?= $row['id'] ?>)">
                                <i class="icon-edit"></i>
                            </div>
                            <a href="<?= URL_PATH ?>/calendar?customerId=<?= $row['id'] ?>" class="SnBtn success jsCustomerOption" data-tooltip="Calendarios">
                                <i class="icon-calendar"></i>
                            </a>
                            <div class="SnBtn jsCustomerOption" data-tooltip="Eliminar" onclick="CustomerForm.delete(<?= $row['id'] ?>,'<?= $row['full_name'] ?>')">
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
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="CustomerForm.list(\''.($currentPage - 1). '\',\''.$limitPage.'\')" class="SnPagination-link">Anterior</a></li>';

    if ($startPage > 1) {
        $htmlPaginate   .= '<li class="SnPagination-item"><a href="#" onclick="CustomerForm.list(\'1\',\''.$limitPage.'\')" class="SnPagination-link">1</a></li>';
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
    }

    for ($i = $startPage; $i <= $endPage; $i++) {
        $class  = ($currentPage == $i) ? "active" : "";
        $htmlPaginate   .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="CustomerForm.list(\''.$i. '\',\''.$limitPage.'\')" class="SnPagination-link">' . $i . '</a></li>';
    }

    if ($endPage < $lastPage) {
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
        $htmlPaginate   .= '<li><a href="#" onclick="CustomerForm.list(\''.$lastPage. '\',\''.$limitPage.'\')" class="SnPagination-link">' . $lastPage . '</a></li>';
    }

    $class      = ($currentPage == $lastPage || $totalPage == 0) ? "disabled" : "";
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="CustomerForm.list(\''.($currentPage + 1). '\',\''.$limitPage.'\')" class="SnPagination-link">Siguiente</a></li>';

    $htmlPaginate       .= '</ul></nav>';

    echo  $htmlPaginate;
}
?>