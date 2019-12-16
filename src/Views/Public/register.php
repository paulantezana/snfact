<?php require_once __DIR__ . '/layout/header.php'; ?>

    <div class="Login">
        <?php require_once __DIR__ . '/partials/alertMessage.php' ?>
        <form action="<?= URL_PATH ?>/auth/register" method="post" class="SnForm">
            <div class="SnForm-item required">
                <label for="registerRuc" class="SnForm-label">RUC</label>
                <input type="text" class="SnForm-control" name="register[ruc]" id="registerRuc" required>
            </div>
            <div class="SnForm-item required">
                <label for="registerEmail" class="SnForm-label">Email</label>
                <input type="email" class="SnForm-control" id="registerEmail" name="register[email]">
            </div>
            <div class="SnForm-item required">
                <label for="registerUserName" class="SnForm-label">Nombre de usuario</label>
                <input type="text" class="SnForm-control" id="registerUserName" name="register[userName]">
            </div>
            <div class="SnForm-item required">
                <label for="registerPassword" class="SnForm-label">Contraseña</label>
                <div class="SnControl-wrapper">
                    <input type="password" class="SnForm-control SnControl" id="registerPassword" name="register[password]">
                    <span class="SnControl-suffix icon-eye togglePassword"></span>
                </div>
            </div>
            <div class="SnForm-item required">
                <label for="registerPasswordConfirm" class="SnForm-label">Confirmar contraseña</label>
                <div class="SnControl-wrapper">
                    <input type="password" class="SnForm-control SnControl" id="registerPasswordConfirm" name="register[passwordConfirm]">
                    <span class="SnControl-suffix icon-eye togglePassword"></span>
                </div>
            </div>

            <input type="submit" value="Registrarse" name="commit" class="SnBtn primary block SnMb-16">
            <a href="<?= URL_PATH ?>/login" class="SnBtn block">Login</a>
        </form>
    </div>

<?php require_once __DIR__ . '/layout/footer.php' ?>