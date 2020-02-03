<?php require_once __DIR__ . '/layout/header.php'; ?>
<div class="SnContentAside UserRole">
    <div class="SnContentAside-left">
        <div class="SnCard">
            <div class="SnCard-body">
                <div class="SnToolbar">
                    <div class="SnToolbar-left">
                        <i class="icon-braille"></i> Roles
                    </div>
                    <div class="SnToolbar-right SnBtns">
                        <div
                            data-tooltip="Recargar lista"
                            class="SnBtn jsUserRoleOption"
                            onclick="UserRoleForm.list()">
                            <i class="icon-refresh"></i>
                        </div>
                        <div
                            data-tooltip="Crear nuevo rol"
                            class="SnBtn primary jsUserRoleOption"
                            onclick="UserRoleForm.showModalCreate()">
                            Nuevo
                        </div>
                    </div>
                </div>

                <div id="userRoleListContainer"></div>
            </div>
        </div>
    </div>
    <div class="SnContentAside-right">
        <div class="SnCard">
            <div class="SnCard-body">
                <div class="SnToolbar">
                    <div class="SnToolbar-left">
                        <i class="icon-braille"></i>
                        <strong>Permisos del : </strong>
                        <span id="userRoleAuthTitle"></span>
                    </div>
                    <div class="SnToolbar-right">
                    </div>
                </div>
                <div id="userRoleAuthList">
                    <div class="SnTable-container SnMb-16">
                        <div class="SnTable-wrapper">
                            <table class="SnTable">
                                <thead>
                                    <tr>
                                        <th>Modulo</th>
                                        <th>Accion</th>
                                        <th>Descripcion</th>
                                        <th style="width: 50px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($appAuthorization ?? [] as $row): ?>
                                        <tr data-id="<?= $row['app_authorization_id'] ?>">
                                            <td><?= $row['module'] ?></td>
                                            <td><?= $row['action'] ?></td>
                                            <td><?= $row['description'] ?></td>
                                            <td>
                                                <div class="SnSwitch" style="height: 18px">
                                                    <input class="SnSwitch-input" type="checkbox" id="autState<?= $row['app_authorization_id']?>" type="checkbox">
                                                    <label class="SnSwitch-label" for="autState<?= $row['app_authorization_id']?>"></label>
                                                </div>  
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <button class="SnBtn primary hidden block jsUserRoleOption" id="userRoleAuthSave" onclick="UserRoleForm.saveAuthorization()" >Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= URL_PATH ?>/assets/script/company/userRole.js"></script>

<div class="SnModal-wrapper" data-modal="userRoleModalForm">
    <div class="SnModal">
        <div class="SnModal-close" data-modalclose="userRoleModalForm">
            <svg viewBox="64 64 896 896" class="" data-icon="close" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false">
                <path d="M563.8 512l262.5-312.9c4.4-5.2.7-13.1-6.1-13.1h-79.8c-4.7 0-9.2 2.1-12.3 5.7L511.6 449.8 295.1 191.7c-3-3.6-7.5-5.7-12.3-5.7H203c-6.8 0-10.5 7.9-6.1 13.1L459.4 512 196.9 824.9A7.95 7.95 0 0 0 203 838h79.8c4.7 0 9.2-2.1 12.3-5.7l216.5-258.1 216.5 258.1c3 3.6 7.5 5.7 12.3 5.7h79.8c6.8 0 10.5-7.9 6.1-13.1L563.8 512z"></path>
            </svg>
        </div>
        <div class="SnModal-header">Rol</div>
        <div class="SnModal-body">
            <form action="" class="SnForm" id="userRoleForm" onsubmit="UserRoleForm.submit(event)">
                <input type="hidden" class="SnForm-control" id="userRoleFormId">
                <div class="SnForm-item">
                    <label for="userRoleFormName" class="SnForm-label">Nombre</label>
                    <input type="text" class="SnForm-control" id="userRoleFormName">
                </div>
                <div class="SnForm-item">
                    <button type="submit" class="SnBtn primary block" id="userRoleFormSubmit">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/layout/footer.php'; ?>