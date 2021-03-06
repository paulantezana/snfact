<div class="Login">
    <?php require_once __DIR__ . '/../partials/alertMessage.php' ?>
    <form action="<?= URL_PATH ?>/login/fa2" method="post" class="SnForm">
        <input type="text" value="<?= $user['user_id'] ?? 0 ?>">
        <div class="SnForm-item">
            <label for="user2faKey" class="SnForm-label">Código de inicio de sesión</label>
            <input type="text" class="SnForm-input" name="user2faKey" id="user2faKey">
        </div>
        <button type="submit" class="SnBtn block primary SnMb-16" name="commit">Continuar</button>
        <a href="<?= URL_PATH ?>/manager/logout" class="SnBtn block">Cancelar</a>
    </form>
</div>