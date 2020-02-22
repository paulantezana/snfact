<div class="SnTable-wrapper">
    <table class="SnTable" id="categoryCurrentTable">
        <thead>
            <tr>
                <th>Categoria</th>
                <th>Descripción</th>
                <th style="width: 100px">Estado</th>
                <th style="width: 100px"></th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($category['data']) >= 1): foreach ($category['data'] as $row) : ?>
                <tr>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['description'] ?></td>
                    <td>
                        <div class="SnSwitch" style="height: 18px">
                            <input class="SnSwitch-control" type="checkbox" id="categoryState<?= $row['category_id']?>" type="checkbox" <?php echo $row['state'] ? 'checked' : '' ?> disabled>
                            <label class="SnSwitch-label" for="categoryState<?= $row['category_id']?>"></label>
                        </div>
                    </td>
                    <td>
                        <div class="SnTable-action">
                            <div class="SnBtn icon jsCategoryAction" data-tooltip="Editar" onclick="CategoryShowModalUpdate(<?= $row['category_id'] ?>)">
                                <i class="icon-pencil"></i>
                            </div>
                            <div class="SnBtn icon jsCategoryAction" data-tooltip="Eliminar" onclick="CategoryDelete(<?= $row['category_id'] ?>,'<?= $row['name'] ?>')">
                                <i class="icon-trash"></i>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; else: ?>
                <tr>
                    <td colspan="4">
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
$currentPage = $category['current'];
$totalPage = $category['pages'];
$limitPage = $category['limit'];
$additionalQuery = '';
$linksQuantity = 3;

if ($totalPage > 1) {
    $lastPage       = $totalPage;
    $startPage      = (($currentPage - $linksQuantity) > 0) ? $currentPage - $linksQuantity : 1;
    $endPage        = (($currentPage + $linksQuantity) < $lastPage) ? $currentPage + $linksQuantity : $lastPage;

    $htmlPaginate       = '<nav aria-label="..."><ul class="SnPagination">';

    $class      = ($currentPage == 1) ? "disabled" : "";
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="CategoryList(\''.($currentPage - 1). '\',\''.$limitPage.'\')" class="SnPagination-link">Anterior</a></li>';

    if ($startPage > 1) {
        $htmlPaginate   .= '<li class="SnPagination-item"><a href="#" onclick="CategoryList(\'1\',\''.$limitPage.'\')" class="SnPagination-link">1</a></li>';
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
    }

    for ($i = $startPage; $i <= $endPage; $i++) {
        $class  = ($currentPage == $i) ? "active" : "";
        $htmlPaginate   .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="CategoryList(\''.$i. '\',\''.$limitPage.'\')" class="SnPagination-link">' . $i . '</a></li>';
    }

    if ($endPage < $lastPage) {
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
        $htmlPaginate   .= '<li><a href="#" onclick="CategoryList(\''.$lastPage. '\',\''.$limitPage.'\')" class="SnPagination-link">' . $lastPage . '</a></li>';
    }

    $class      = ($currentPage == $lastPage || $totalPage == 0) ? "disabled" : "";
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="CategoryList(\''.($currentPage + 1). '\',\''.$limitPage.'\')" class="SnPagination-link">Siguiente</a></li>';

    $htmlPaginate       .= '</ul></nav>';

    echo  $htmlPaginate;
}
?>