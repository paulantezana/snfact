<?php require_once __DIR__ . '/layout/header.php'; ?>

    <div class="Login">
        <?php require_once __DIR__ . '/partials/alertMessage.php' ?>
        <form action="<?= URL_PATH ?>/auth/posLogin" method="post" class="SnForm">
            <div class="SnForm-item">
                <label for="user2faKey" class="SnForm-label">Código de inicio de sesión</label>
                <input type="text" class="SnForm-input" name="user2faKey" id="user2faKey">
            </div>
            <button type="submit" class="SnBtn block primary SnMb-16" name="commit">Continuar</button>
            <a href="<?= URL_PATH ?>/auth/logout" class="SnBtn block">Cancelar</a>
        </form>
    </div>

<?php require_once __DIR__ . '/layout/footer.php' ?>