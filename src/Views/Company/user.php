<?php require_once __DIR__ . '/layout/header.php'; ?>
<div class="SnContent">
    <div class="SnToolbar">
        <div class="SnToolbar-left">
            <i class="icon-angle-right"></i> Usuarios
        </div>
        <div class="SnToolbar-right">
            <div class="SnBtn jsUserOption" onclick="UserForm.list()">
                <i class="icon-refresh"></i>
                Actualizar
            </div>
            <div class="SnBtn primary jsUserOption" onclick="UserForm.showModalCreate()" >
                <i class="icon-plus"></i>
                Nuevo
            </div>
        </div>
    </div>
    <div class="SnCard">
        <div class="SnCard-body">
            <div class="SnControl-wrapper SnMb-16">
                <input type="text" class="SnForm-control SnControl" onkeyup="UserForm.search(event)">
                <span class="SnControl-suffix icon-search"></span>
            </div>
            <div id="userTable"></div>
        </div>
    </div>
</div>

<script src="<?= URL_PATH ?>/assets/script/company/user.js"></script>

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
                <input type="hidden" class="SnForm-control" id="userId">
                <div class="SnForm-item required">
                    <label for="userEmail" class="SnForm-label">Email</label>
                    <input type="email" class="SnForm-control" id="userEmail">
                </div>
                <div class="SnForm-item required">
                    <label for="userUserName" class="SnForm-label">Nombre de usuario</label>
                    <input type="text" class="SnForm-control" id="userUserName">
                </div>
                <div class="SnForm-item required">
                    <label for="userPassword" class="SnForm-label">Contraseña</label>
                    <div class="SnControl-wrapper">
                        <input type="password" class="SnForm-control" id="userPassword">
                        <span class="SnControl-suffix icon-eye togglePassword"></span>
                    </div>
                </div>
                <div class="SnForm-item required">
                    <label for="userPasswordConfirm" class="SnForm-label">Confirmar contraseña</label>
                    <div class="SnControl-wrapper">
                        <input type="password" class="SnForm-control" id="userPasswordConfirm">
                        <span class="SnControl-suffix icon-eye togglePassword"></span>
                    </div>
                </div>
                <div class="SnForm-item required">
                    <label for="userUserRoleId" class="SnForm-label">Rol</label>
                    <select id="userUserRoleId" class="SnForm-select">
                        <option value="">Seleccionar</option>
                        <?php foreach ($userRole ?? [] as $row): ?>
                            <option value="<?= $row['user_role_id'] ?>"><?= $row['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="SnForm-item">
                    <p>Estado</p>
                    <input class="SnSwitch SnSwitch-ios" id="userState" type="checkbox">
                    <label class="SnSwitch-btn" for="userState"></label>
                </div>
                <div class="SnForm-item">
                    <button type="submit" class="SnBtn primary block" id="userFormSubmit">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layout/footer.php' ?>