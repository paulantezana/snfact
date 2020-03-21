<div class="SnContent">
    <div class="SnToolbar">
        <div class="SnToolbar-left">
            <i class=" fas fa-list-ul SnMr-2"></i> <strong>USUARIOS</strong>
        </div>
        <div class="SnToolbar-right">
            <div class="SnBtn jsUserAction" onclick="userToPrint()">
                <i class="icon-printer"></i>
            </div>
            <div class="SnBtn jsUserAction" onclick="userToExcel()">
                <i class="icon-file-excel"></i>
            </div>
            <div class="SnBtn jsUserAction" onclick="userList()">
                <i class="icon-reload-alt"></i>
            </div>
            <div class="SnBtn primary jsUserAction" onclick="userShowModalCreate()">
                <i class="icon-plus2 SnMr-2"></i> Nuevo
            </div>
        </div>
    </div>
    <div class="SnCard">
        <div class="SnCard-body">
            <div class="SnControl-wrapper SnMb-5">
                <input type="text" class="SnForm-control SnControl" id="searchContent" placeholder="Buscar...">
                <span class="SnControl-suffix icon-search4"></span>
            </div>
            <div id="userTable"></div>
        </div>
    </div>
</div>

<script src="<?= URL_PATH ?>/assets/script/company/user.js"></script>

<div class="SnModal-wrapper" data-modal="userModalForm">
    <div class="SnModal">
        <div class="SnModal-close" data-modalclose="userModalForm">
            <i class="fas fa-times"></i>
        </div>
        <div class="SnModal-header"><i class="fas fa-folder-plus SnMr-2"></i> Usuario</div>
        <div class="SnModal-body">
            <form action="" class="SnForm" novalidate id="userForm" onsubmit="userSubmit(event)">
                <input type="hidden" class="SnForm-control" id="userId">
                <div class="SnForm-item required">
                    <label for="userEmail" class="SnForm-label">Email</label>
                    <div class="SnControl-wrapper">
                        <i class="icon-envelop2 SnControl-prefix"></i>
                        <input type="email" class="SnForm-control SnControl" id="userEmail" required>
                    </div>
                </div>
                <div class="SnForm-item required">
                    <label for="userUserName" class="SnForm-label">Nombre de usuario</label>
                    <div class="SnControl-wrapper">
                        <i class="icon-user SnControl-prefix"></i>
                        <input type="text" class="SnForm-control SnControl" id="userUserName" required>
                    </div>
                </div>
                <div class="SnForm-item required">
                    <label for="userPassword" class="SnForm-label">Contraseña</label>
                    <div class="SnControl-wrapper">
                        <i class="icon-key SnControl-prefix"></i>
                        <input type="password" class="SnForm-control SnControl" id="userPassword" required>
                        <span class="SnControl-suffix icon-eye togglePassword"></span>
                    </div>
                </div>
                <div class="SnForm-item required">
                    <label for="userPasswordConfirm" class="SnForm-label">Confirmar contraseña</label>
                    <div class="SnControl-wrapper">
                        <i class="icon-key SnControl-prefix"></i>
                        <input type="password" class="SnForm-control SnControl" id="userPasswordConfirm" required>
                        <span class="SnControl-suffix icon-eye togglePassword"></span>
                    </div>
                </div>
                <div class="SnForm-item required">
                    <label for="userUserRoleId" class="SnForm-label">Rol</label>
                    <select id="userUserRoleId" class="SnForm-control" required>
                        <option value="">Seleccionar</option>
                        <?php foreach ($parameter['userRole'] ?? [] as $row): ?>
                            <option value="<?= $row['user_role_id'] ?>"><?= $row['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="SnForm-item">
                    <div class="SnSwitch">
                        <input class="SnSwitch-control" type="checkbox" id="userState">
                        <label class="SnSwitch-label" for="userState">Estado</label>
                    </div>
                </div>
                <button type="submit" class="SnBtn primary block" id="userFormSubmit">Guardar</button>
            </form>
        </div>
    </div>
</div>