<div class="SnTable-wrapper">
    <table class="SnTable" id="userRoleCurrentTable">
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
                            onclick="userRoleLoadAuthorities(<?= $row['user_role_id'] ?>,'<?= $row['name'] ?>')">
                            <i class="icon-cog"></i>
                        </div>
                        <div
                            class="SnBtn icon jsUserRoleOption"
                            data-tooltip="Editar"
                            onclick="userRoleShowModalUpdate(<?= $row['user_role_id'] ?>,'<?= $row['name'] ?>')">
                            <i class="icon-pencil"></i>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>