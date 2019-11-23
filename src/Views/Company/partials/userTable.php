<div class="SnTable-wrapper">
    <table class="SnTable">
        <thead>
            <tr>
                <th>Nombre completo</th>
                <th>Documento</th>
                <th>Telefono</th>
                <th>Estado</th>
                <th>Perfil</th>
                <th>Estado</th>
                <th style="width: 100px"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($user['data'] as $row) : ?>
                <tr>
                    <td>
                        <div class="SnAvatar">
                            <img src="<?= URL_PATH ?>/assets/images/logo.png" alt="avatar">
                        </div>
                    </td>
                    <td><?= $row['user_name'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['state'] ?></td>
                    <td><?= $row['user_role'] ?></td>
                    <td>
                        <input class="SnSwitch SnSwitch-ios" id="userState<?= $row['user_id']?>" type="checkbox" <?php echo $row['state'] ? 'checked' : '' ?> disabled>
                        <label class="SnSwitch-btn" for="userState<?= $row['user_id']?>"></label>
                    </td>
                    <td>
                        <div class="SnTable-action">
                            <div class="SnBtn jsUserOption" data-tooltip="Cambiar contraseña" onclick="UserForm.executeUpdatePassword(<?= $row['user_id'] ?>)">
                                <i class="icon-key"></i>
                            </div>
                            <div class="SnBtn jsUserOption" data-tooltip="Editar" onclick="UserForm.executeUpdateNormal(<?= $row['user_id'] ?>)">
                                <i class="icon-pencil"></i>
                            </div>
                            <div class="SnBtn jsUserOption" data-tooltip="Eliminar" onclick="UserForm.delete(<?= $row['user_id'] ?>,'<?= $row['user_name'] ?>')">
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
$currentPage = $user['current'];
$totalPage = $user['pages'];
$limitPage = $user['limit'];
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