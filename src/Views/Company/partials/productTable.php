<div class="SnTable-wrapper">
    <table class="SnTable" id="productCurrentTable">
        <thead>
            <tr>
                <th>CÓDIGO</th>
                <th>Categoría</th>
                <th>Producto/Servicio</th>
                <th>Unidad</th>
                <th>TipoAfec.IGV</th>
                <th>Precio Venta</th>
                <th>Estado</th>
                <th style="width: 100px"></th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($product['data']) >= 1): foreach ($product['data'] as $row) : ?>
                <tr>
                    <td><?= $row['product_key'] ?></td>
                    <td><?= $row['category_description'] ?></td>
                    <td><?= $row['description'] ?></td>
                    <td><?= $row['unit_measure_code'] ?></td>
                    <td><?= $row['affectation_igv_description'] ?></td>
                    <td><?= $row['unit_price'] ?></td>
                    <td>
                        <div class="SnSwitch" style="height: 18px">
                            <input class="SnSwitch-input" type="checkbox" id="productState<?= $row['product_id']?>" type="checkbox" <?php echo $row['state'] ? 'checked' : '' ?> disabled>
                            <label class="SnSwitch-label" for="productState<?= $row['product_id']?>"></label>
                        </div>
                    </td>
                    <td>
                        <div class="SnTable-action">
                            <div class="SnBtn icon jsCategoryAction" data-tooltip="Editar" onclick="ProductShowModalUpdate(<?= $row['product_id'] ?>)">
                                <i class="icon-pencil"></i>
                            </div>
                            <div class="SnBtn icon jsCategoryAction" data-tooltip="Eliminar" onclick="ProductDelete(<?= $row['product_id'] ?>,'<?= $row['description'] ?>')">
                                <i class="icon-trash"></i>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; else: ?>
                <tr>
                    <td colspan="8">
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
$currentPage = $product['current'];
$totalPage = $product['pages'];
$limitPage = $product['limit'];
$additionalQuery = '';
$linksQuantity = 3;

if ($totalPage > 1) {
    $lastPage       = $totalPage;
    $startPage      = (($currentPage - $linksQuantity) > 0) ? $currentPage - $linksQuantity : 1;
    $endPage        = (($currentPage + $linksQuantity) < $lastPage) ? $currentPage + $linksQuantity : $lastPage;

    $htmlPaginate       = '<nav aria-label="..."><ul class="SnPagination">';

    $class      = ($currentPage == 1) ? "disabled" : "";
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="ProductList(\''.($currentPage - 1). '\',\''.$limitPage.'\')" class="SnPagination-link">Anterior</a></li>';

    if ($startPage > 1) {
        $htmlPaginate   .= '<li class="SnPagination-item"><a href="#" onclick="ProductList(\'1\',\''.$limitPage.'\')" class="SnPagination-link">1</a></li>';
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
    }

    for ($i = $startPage; $i <= $endPage; $i++) {
        $class  = ($currentPage == $i) ? "active" : "";
        $htmlPaginate   .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="ProductList(\''.$i. '\',\''.$limitPage.'\')" class="SnPagination-link">' . $i . '</a></li>';
    }

    if ($endPage < $lastPage) {
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
        $htmlPaginate   .= '<li><a href="#" onclick="ProductList(\''.$lastPage. '\',\''.$limitPage.'\')" class="SnPagination-link">' . $lastPage . '</a></li>';
    }

    $class      = ($currentPage == $lastPage || $totalPage == 0) ? "disabled" : "";
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="ProductList(\''.($currentPage + 1). '\',\''.$limitPage.'\')" class="SnPagination-link">Siguiente</a></li>';

    $htmlPaginate       .= '</ul></nav>';

    echo  $htmlPaginate;
}
?>