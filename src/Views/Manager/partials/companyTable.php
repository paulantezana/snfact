<div class="SnTable-wrapper">
    <table class="SnTable" id="companyCurrentTable">
        <thead>
            <tr>
                <th>Logo</th>
                <th>Email</th>
                <th>Empresa</th>
                <th>Phone</th>
                <th>Sucursales</th>
                <th style="width: 100px">Condición</th>
                <th style="width: 100px"></th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($parameter['company']['data']) >= 1) : foreach ($parameter['company']['data'] as $row) : ?>
                    <tr>
                        <td>
                            <img src="<?php echo URL_PATH . $row['logo'] ?>" alt="logo" style="max-height: 45px">
                        </td>
                        <td><?= $row['email'] ?></td>
                        <td>
                            <div><strong><?= $row['ruc'] ?></strong></div>
                            <div><?= $row['social_reason'] ?></div>
                        </td>
                        <td><?= $row['phone'] ?></td>
                        <td></td>
                        <td>
                            <div class="SnMb-2">
                                <?php if ($row['environment'] == '1') : ?>
                                    <span class="SnTag success">producción</span>
                                <?php else : ?>
                                    <span class="SnTag error">producción</span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <?php if ($row['state'] == '1') : ?>
                                    <span class="SnTag success">estado</span>
                                <?php else : ?>
                                    <span class="SnTag error">estado</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div class="SnTable-action">
                                <div class="SnBtn icon jsCompanyAction" data-tooltip="Editar" onclick="CompanyShowModalUpdate(<?= $row['business_id'] ?>)">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <div class="SnBtn icon jsCompanyAction" data-tooltip="Logo" onclick="CompanyShowModalLogo(<?= $row['business_id'] ?>)">
                                    <i class="fas fa-camera"></i>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach;
            else : ?>
                <tr>
                    <td colspan="7">
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
$currentPage = $parameter['company']['current'];
$totalPage = $parameter['company']['pages'];
$limitPage = $parameter['company']['limit'];
$additionalQuery = '';
$linksQuantity = 3;

if ($totalPage > 1) {
    $lastPage       = $totalPage;
    $startPage      = (($currentPage - $linksQuantity) > 0) ? $currentPage - $linksQuantity : 1;
    $endPage        = (($currentPage + $linksQuantity) < $lastPage) ? $currentPage + $linksQuantity : $lastPage;

    $htmlPaginate       = '<nav aria-label="..."><ul class="SnPagination">';

    $class      = ($currentPage == 1) ? "disabled" : "";
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="CompanyList(\'' . ($currentPage - 1) . '\',\'' . $limitPage . '\')" class="SnPagination-link">Anterior</a></li>';

    if ($startPage > 1) {
        $htmlPaginate   .= '<li class="SnPagination-item"><a href="#" onclick="CompanyList(\'1\',\'' . $limitPage . '\')" class="SnPagination-link">1</a></li>';
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
    }

    for ($i = $startPage; $i <= $endPage; $i++) {
        $class  = ($currentPage == $i) ? "active" : "";
        $htmlPaginate   .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="CompanyList(\'' . $i . '\',\'' . $limitPage . '\')" class="SnPagination-link">' . $i . '</a></li>';
    }

    if ($endPage < $lastPage) {
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
        $htmlPaginate   .= '<li><a href="#" onclick="CompanyList(\'' . $lastPage . '\',\'' . $limitPage . '\')" class="SnPagination-link">' . $lastPage . '</a></li>';
    }

    $class      = ($currentPage == $lastPage || $totalPage == 0) ? "disabled" : "";
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="CompanyList(\'' . ($currentPage + 1) . '\',\'' . $limitPage . '\')" class="SnPagination-link">Siguiente</a></li>';

    $htmlPaginate       .= '</ul></nav>';

    echo  $htmlPaginate;
}
?>