<div class="SnContentAside UserRole">
    <div class="SnContentAside-left">
        <div class="SnCard">
            <div class="SnCard-body">
                <div class="SnToolbar">
                    <div class="SnToolbar-left">
                        <i class="fas fa-list-ul SnMr-2"></i> Roles
                    </div>
                    <div class="SnToolbar-right SnBtns">
                        <div
                            data-tooltip="Recargar lista"
                            class="SnBtn jsUserRoleOption"
                            onclick="userRoleList()">
                            <i class="icon-reload-alt"></i>
                        </div>
                        <div
                            data-tooltip="Crear nuevo rol"
                            class="SnBtn primary jsUserRoleOption"
                            onclick="userRoleShowModalCreate()">
                            <i class="icon-plus2 SnMr-2"></i>Nuevo
                        </div>
                    </div>
                </div>

                <div id="userRoleTable"></div>
            </div>
        </div>
    </div>
    <div class="SnContentAside-right">
        <div class="SnCard">
            <div class="SnCard-body">
                <div class="SnToolbar">
                    <div class="SnToolbar-left">
                        <i class="fas fa-list-ul SnMr-2"></i>
                        <strong>Permisos del : </strong>
                        <span id="userRoleAuthTitle"></span>
                    </div>
                    <div class="SnToolbar-right">
                    </div>
                </div>
                <div id="userRoleAuthList">
                    <div class="SnTable-container SnMb-5">
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
                                    <?php foreach ($parameter['appAuthorization'] ?? [] as $row): ?>
                                        <tr data-id="<?= $row['app_authorization_id'] ?>">
                                            <td><?= $row['module'] ?></td>
                                            <td><?= $row['action'] ?></td>
                                            <td><?= $row['description'] ?></td>
                                            <td>
                                                <div class="SnSwitch" style="height: 18px">
                                                    <input class="SnSwitch-control" type="checkbox" id="autState<?= $row['app_authorization_id']?>">
                                                    <label class="SnSwitch-label" for="autState<?= $row['app_authorization_id']?>"></label>
                                                </div>  
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <button class="SnBtn primary hidden block jsUserRoleOption" id="userRoleAuthSave" onclick="userRoleSaveAuthorization()" >Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= URL_PATH ?>/assets/script/company/userRole.js"></script>

<div class="SnModal-wrapper" data-modal="userRoleModalForm">
    <div class="SnModal">
        <div class="SnModal-close" data-modalclose="userRoleModalForm">
            <i class="fas fa-times"></i>
        </div>
        <div class="SnModal-header"><i class="fas fa-folder-plus SnMr-2"></i>Rol</div>
        <div class="SnModal-body">
            <form action="" class="SnForm" novalidate id="userRoleForm" onsubmit="userRoleSubmit()">
                <input type="hidden" class="SnForm-control" id="userRoleFormId">
                <div class="SnForm-item required">
                    <label for="userRoleName" class="SnForm-label">Descripcion</label>
                    <div class="SnControl-wrapper">
                        <i class="icon-file-text2 SnControl-prefix"></i>
                        <input type="text" class="SnForm-control SnControl" id="userRoleName" required>
                    </div>
                </div>
                <div class="SnForm-item">
                    <div class="SnSwitch">
                        <input class="SnSwitch-control" type="checkbox" id="userRoleState">
                        <label class="SnSwitch-label" for="userRoleState">Estado</label>
                    </div>
                </div>
                <button type="submit" class="SnBtn primary block" id="userRoleFormSubmit">Guardar</button>
            </form>
        </div>
    </div>
</div>