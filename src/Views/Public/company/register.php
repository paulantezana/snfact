<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="Login">
    <?php require_once __DIR__ . '/../partials/alertMessage.php' ?>
    <form action="" method="post" class="SnForm">
        <div class="SnForm-item required">
            <label for="registerRuc" class="SnForm-label">RUC</label>
            <div class="SnControl-wrapper">
                <i class="icon-barcode2 SnControl-prefix"></i>
                <input type="text" class="SnForm-control SnControl" required id="registerRuc" name="register[ruc]" placeholder="Nombre de usuario">
            </div>
        </div>
        <div class="SnForm-item required">
            <label for="registerEmail" class="SnForm-label">Email</label>
            <div class="SnControl-wrapper">
                <i class="icon-envelop2 SnControl-prefix"></i>
                <input type="email" class="SnForm-control SnControl" required id="registerEmail" name="register[email]" placeholder="Email">
            </div>
        </div>
        <div class="SnForm-item required">
            <label for="registerUserName" class="SnForm-label">Nombre de usuario</label>
            <div class="SnControl-wrapper">
                <i class="icon-user SnControl-prefix"></i>
                <input type="text" class="SnForm-control SnControl" required id="registerUserName" name="register[userName]" placeholder="Nombre de usuario">
            </div>
        </div>
        <div class="SnForm-item required">
            <label for="registerPassword" class="SnForm-label">Contraseña</label>
            <div class="SnControl-wrapper">
                <i class="icon-key SnControl-prefix"></i>
                <input type="password" class="SnForm-control SnControl" id="registerPassword" name="register[password]" placeholder="Contraseña">
                <span class="SnControl-suffix icon-eye togglePassword"></span>
            </div>
        </div>
        <div class="SnForm-item required">
            <label for="registerPasswordConfirm" class="SnForm-label">Confirmar contraseña</label>
            <div class="SnControl-wrapper">
                <i class="icon-key SnControl-prefix"></i>
                <input type="password" class="SnForm-control SnControl" id="registerPasswordConfirm" name="register[passwordConfirm]" placeholder="Confirmar contraseña">
                <span class="SnControl-suffix icon-eye togglePassword"></span>
            </div>
        </div>
        <input type="submit" value="Registrarse" name="commit" class="SnBtn primary block SnMb-5">
        <a href="<?= URL_PATH ?>/login" class="SnBtn block">Login</a>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php' ?>