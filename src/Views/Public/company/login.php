<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="Login">
    <?php require_once __DIR__ . '/../partials/alertMessage.php' ?>
    <form action="" method="post" class="SnForm">
        <div class="SnForm-item required">
            <label for="username" class="SnForm-label">Nombre de usuario</label>
            <div class="SnControl-wrapper">
                <i class="icon-user SnControl-prefix"></i>
                <input type="text" class="SnForm-control SnControl" required id="username" name="user" placeholder="Nombre de usuario">
            </div>
        </div>
        <div class="SnForm-item required">
            <label for="password" class="SnForm-label">Contraseña</label>
            <div class="SnControl-wrapper">
                <i class="icon-lock SnControl-prefix"></i>
                <input type="password" class="SnForm-control SnControl" required id="password" name="password" placeholder="Contraseña">
                <span class="SnControl-suffix icon-eye togglePassword"></span>
            </div>
        </div>
        <div class="SnForm-item Login-flex">
            <div class="SnSwitch">
                <input class="SnSwitch-input " id="remember" name="remember" type="checkbox">
                <label class="SnSwitch-label" for="remember">Recuerdame</label>
            </div>
            <a href="<?= URL_PATH ?>/forgot"> ¿Olvido su contraseña?</a>
        </div>
        <button type="submit" class="SnBtn block primary" name="commit">Login</button>
        <p>O <a href="<?= URL_PATH ?>/register"> Registrate ahora</a></p>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php' ?>