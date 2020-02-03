<div class="SnTable-wrapper">
    <table class="SnTable">
        <thead>
        <tr>
            <th>Nombre</th>
            <th style="width: 100px"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($userRole ?? [] as $row): ?>
            <tr>
                <td><?= $row['name'] ?></td>
                <td>
                    <div class="SnTable-action">
                        <div
                            class="SnBtn icon primary jsUserRoleOption"
                            data-tooltip="Configurar permisos"
                            onclick="UserRoleForm.loadAuthorities(<?= $row['user_role_id'] ?>,'<?= $row['name'] ?>')">
                            <i class="icon-cog"></i>
                        </div>
                        <div
                            class="SnBtn icon jsUserRoleOption"
                            data-tooltip="Editar"
                            onclick="UserRoleForm.showModalUpdate(<?= $row['user_role_id'] ?>,'<?= $row['name'] ?>')">
                            <i class="icon-pencil"></i>
                        </div>
                        <div
                            data-tooltip="Eliminar"
                            class="SnBtn icon jsUserRoleOption"
                            onclick="UserRoleForm.delete(<?= $row['user_role_id'] ?>,'<?= $row['name'] ?>')" >
                            <i class="icon-trash"></i>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>