<div class="SnTable-wrapper">
    <table class="SnTable">
        <thead>
            <tr>
                <th>Nombre</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($routes as $row) : ?>
                <tr>
                    <td><?= $row['name'] ?></td>
                    <td>
                        <div class="SnTable-action">
                            <div class="SnBtn sm error jsCalendarRouteOption" data-tooltip="Eliminar" onclick="CalendarRouteForm.delete(<?= $row['id'] ?>,'<?= $row['name'] ?>')">
                                <i class="icon-trash"></i>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>