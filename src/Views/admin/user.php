<?php require_once __DIR__ . '/layout/header.php'; ?>
<div class="SnContent">
    <div class="SnToolbar">
        <div class="SnToolbar-left">
            <i class="icon-braille"></i> Usuarios
        </div>
        <div class="SnToolbar-right">
            <!--                <div class="SnBtn">-->
            <!--                    <i class="icon-refresh"></i>-->
            <!--                    Actualizar-->
            <!--                </div>-->
            <div class="SnBtn primary jsUserOption" onclick="UserForm.showModalCreate()">
                <i class="icon-plus"></i>
                Nuevo
            </div>
        </div>
    </div>
    <div class="SnCard">
        <div class="SnCard-body">
            <div class="SnTable-wrapper">
                <table class="SnTable">
                    <thead>
                        <tr>
                            <th>Avatar</th>
                            <th>Usuario</th>
                            <th>Email</th>
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
                                <td>
                                    <div class="SnTable-action">
                                        <div class="SnBtn jsUserOption" onclick="UserForm.executeUpdatePassword(<?= $row['id'] ?>)">
                                            <i class="icon-lock"></i>
                                        </div>
                                        <div class="SnBtn jsUserOption" onclick="UserForm.executeUpdateNormal(<?= $row['id'] ?>)">
                                            <i class="icon-edit"></i>
                                        </div>
                                        <div class="SnBtn jsUserOption" onclick="UserForm.delete(<?= $row['id'] ?>,'<?= $row['user_name'] ?>')">
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
                $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="?limit=' . $limitPage . '&page=' . ($currentPage - 1) . $additionalQuery . '" class="SnPagination-link">Anterior</a></li>';

                if ($startPage > 1) {
                    $htmlPaginate   .= '<li class="SnPagination-item"><a href="?limit=' . $limitPage . '&page=1' . $additionalQuery . '" class="SnPagination-link">1</a></li>';
                    $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
                }

                for ($i = $startPage; $i <= $endPage; $i++) {
                    $class  = ($currentPage == $i) ? "active" : "";
                    $htmlPaginate   .= '<li class="SnPagination-item ' . $class . '"><a href="?limit=' . $limitPage . '&page=' . $i . $additionalQuery . '" class="SnPagination-link">' . $i . '</a></li>';
                }

                if ($endPage < $lastPage) {
                    $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
                    $htmlPaginate   .= '<li><a href="?limit=' . $limitPage . '&page=' . $lastPage . $additionalQuery . '" class="SnPagination-link">' . $lastPage . '</a></li>';
                }

                $class      = ($currentPage == $lastPage || $totalPage == 0) ? "disabled" : "";
                $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="?limit=' . $limitPage . '&page=' . ($currentPage + 1) . $additionalQuery . '" class="SnPagination-link">Siguiente</a></li>';

                $htmlPaginate       .= '</ul></nav>';

                echo  $htmlPaginate;
            }
            ?>

        </div>
    </div>
</div>
<script src="<?= URL_PATH ?>/assets/build/script/user-min.js"></script>


<div class="SnModal-wrapper" data-modal="userModalForm">
    <div class="SnModal">
        <div class="SnModal-close" data-modalclose="userModalForm">
            <svg viewBox="64 64 896 896" class="" data-icon="close" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false">
                <path d="M563.8 512l262.5-312.9c4.4-5.2.7-13.1-6.1-13.1h-79.8c-4.7 0-9.2 2.1-12.3 5.7L511.6 449.8 295.1 191.7c-3-3.6-7.5-5.7-12.3-5.7H203c-6.8 0-10.5 7.9-6.1 13.1L459.4 512 196.9 824.9A7.95 7.95 0 0 0 203 838h79.8c4.7 0 9.2-2.1 12.3-5.7l216.5-258.1 216.5 258.1c3 3.6 7.5 5.7 12.3 5.7h79.8c6.8 0 10.5-7.9 6.1-13.1L563.8 512z"></path>
            </svg>
        </div>
        <div class="SnModal-header">Usuario</div>
        <div class="SnModal-body">
            <form action="" class="SnForm" id="userForm" onsubmit="UserForm.submit(event)">
                <input type="hidden" class="SnForm-input" id="userId">
                <div class="SnForm-item required">
                    <label for="userEmail" class="SnForm-label">Email</label>
                    <input type="email" class="SnForm-input" id="userEmail">
                </div>
                <div class="SnForm-item required">
                    <label for="userUserName" class="SnForm-label">Nombre de usuario</label>
                    <input type="text" class="SnForm-input" id="userUserName">
                </div>
                <div class="SnForm-item required">
                    <label for="userPassword" class="SnForm-label">Contraseña</label>
                    <div class="SnInput-wrapper">
                        <input type="password" class="SnForm-input" id="userPassword">
                        <span class="SnInput-suffix icon-eye togglePassword"></span>
                    </div>
                </div>
                <div class="SnForm-item required">
                    <label for="userPasswordConfirm" class="SnForm-label">Confirmar contraseña</label>
                    <div class="SnInput-wrapper">
                        <input type="password" class="SnForm-input" id="userPasswordConfirm">
                        <span class="SnInput-suffix icon-eye togglePassword"></span>
                    </div>
                </div>
                <div class="SnForm-item required">
                    <label for="userUserRoleId" class="SnForm-label">Rol</label>
                    <select id="userUserRoleId" class="SnForm-select">
                        <option value="">Seleccionar</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>
                <div class="SnForm-item">
                    <label for="userState" class="SnForm-label">Estado</label>
                    <input type="checkbox" class="SnForm-input" id="userState">
                </div>
                <div class="SnForm-item">
                    <button type="submit" class="SnBtn primary block" id="userFormSubmit">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/layout/footer.php'
?>