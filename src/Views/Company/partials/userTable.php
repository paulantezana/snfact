<div class="SnTable-wrapper">
    <table class="SnTable" id="userCurrentTable">
        <thead>
            <tr>
                <th style="width: 40px">Avatar</th>
                <th>Nombre completo</th>
                <th>Telefono</th>
                <th>Estado</th>
                <th>Perfil</th>
                <th>Estado</th>
                <th style="width: 100px"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($parameter['user']['data'] as $row) : ?>
                <tr>
                    <td>
                        <div class="SnAvatar">
                            <img src="<?= URL_PATH ?>/assets/images/icon/Icon-144.png" alt="avatar">
                        </div>
                    </td>
                    <td><?= $row['user_name'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['state'] ?></td>
                    <td><?= $row['user_role'] ?></td>
                    <td>
                        <div class="SnSwitch" style="height: 18px">
                            <input class="SnSwitch-control" type="checkbox" id="userState<?= $row['user_id']?>" type="checkbox" <?php echo $row['state'] ? 'checked' : '' ?> disabled>
                            <label class="SnSwitch-label" for="userState<?= $row['user_id']?>"></label>
                        </div>
                    </td>
                    <td>
                        <div class="SnTable-action">
                            <div class="SnBtn icon jsUserOption" data-tooltip="Cambiar contraseña" onclick="userShowModalUpdatePassword(<?= $row['user_id'] ?>)">
                                <i class="icon-key"></i>
                            </div>
                            <div class="SnBtn icon jsUserOption" data-tooltip="Editar" onclick="userShowModalUpdate(<?= $row['user_id'] ?>)">
                                <i class="icon-pencil"></i>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php
$currentPage = $parameter['user']['current'];
$totalPage = $parameter['user']['pages'];
$limitPage = $parameter['user']['limit'];
$additionalQuery = '';
$linksQuantity = 3;

if ($totalPage > 1) {
    $lastPage       = $totalPage;
    $startPage      = (($currentPage - $linksQuantity) > 0) ? $currentPage - $linksQuantity : 1;
    $endPage        = (($currentPage + $linksQuantity) < $lastPage) ? $currentPage + $linksQuantity : $lastPage;

    $htmlPaginate       = '<nav aria-label="..."><ul class="SnPagination">';

    $class      = ($currentPage == 1) ? "disabled" : "";
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="UserForm.list(\''.($currentPage - 1). '\',\''.$limitPage.'\')" class="SnPagination-link">Anterior</a></li>';

    if ($startPage > 1) {
        $htmlPaginate   .= '<li class="SnPagination-item"><a href="#" onclick="UserForm.list(\'1\',\''.$limitPage.'\')" class="SnPagination-link">1</a></li>';
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
    }

    for ($i = $startPage; $i <= $endPage; $i++) {
        $class  = ($currentPage == $i) ? "active" : "";
        $htmlPaginate   .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="UserForm.list(\''.$i. '\',\''.$limitPage.'\')" class="SnPagination-link">' . $i . '</a></li>';
    }

    if ($endPage < $lastPage) {
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
        $htmlPaginate   .= '<li><a href="#" onclick="UserForm.list(\''.$lastPage. '\',\''.$limitPage.'\')" class="SnPagination-link">' . $lastPage . '</a></li>';
    }

    $class      = ($currentPage == $lastPage || $totalPage == 0) ? "disabled" : "";
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="UserForm.list(\''.($currentPage + 1). '\',\''.$limitPage.'\')" class="SnPagination-link">Siguiente</a></li>';

    $htmlPaginate       .= '</ul></nav>';

    echo  $htmlPaginate;
}
?>